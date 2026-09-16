<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Home;
use App\Livewire\Login;
use App\Livewire\Register;
use App\Livewire\ForgotPassword;
use App\Livewire\ResetPassword;
use App\Livewire\Dashboard;
use App\Livewire\Products;
use App\Livewire\Categories;
use App\Livewire\Descuentos;
use App\Livewire\Configuracion;
use App\Livewire\Catalogo;
use App\Livewire\Cart;
use App\Livewire\ShowProduct;
use App\Livewire\Cuenta;
use App\Livewire\Usuarios;
use App\Livewire\Planes;
use App\Livewire\Subscripciones;
use App\Livewire\EditItem;
use App\Livewire\CreateItem;
use App\Livewire\Orders;
use App\Http\Controllers\LanguageController;
use Illuminate\Http\Request;
use App\Models\Catalogo as CatalogoModel;
use App\Models\Cart as CartModel;
use App\Models\CartItem as CartItemModel;
use App\Models\Product as ProductModel;
use App\Models\Order as OrderModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

Route::get('/', Home::class)->middleware(['guest'])->name('home');
Route::get('/Login', Login::class)->middleware(['guest'])->name('login');
Route::get('/Register', Register::class)->middleware(['guest'])->name('register');
Route::get('/forgot-password', ForgotPassword::class)->middleware(['guest'])->name('password.request');
Route::get('/reset-password/{token}', ResetPassword::class)->middleware(['guest'])->name('password.reset');
Route::view('/terminos-y-condiciones', 'legal.terms')->name('terms');

Route::get('/Dashboard', Dashboard::class)->middleware(['auth'])->name('dashboard');
Route::get('/Products', Products::class)->middleware(['auth'])->name('products');
Route::get('/Orders', Orders::class)->middleware(['auth'])->name('orders');
Route::get('/Categories', Categories::class)->middleware(['auth'])->name('categories');
Route::get('/Descuentos', Descuentos::class)->middleware(['auth'])->name('descuentos');
Route::get('/Configuracion', Configuracion::class)->middleware(['auth'])->name('configuracion');
Route::get('/Cuenta', Cuenta::class)->middleware(['auth'])->name('cuenta');

Route::get('/Users', Usuarios::class)->middleware(['auth'])->name('usuarios');
Route::get('/Planes', Planes::class)->middleware(['auth'])->name('planes');
Route::get('/Subscripciones', Subscripciones::class)->middleware(['auth'])->name('subscripciones');

Route::get('/Crear/{model}', CreateItem::class)->middleware(['auth'])->name('create');
Route::get('/Editar/{model}/{id}', EditItem::class)->middleware(['auth'])->name('edit');

Route::get('/lang/{locale}', [LanguageController::class, 'setLocale'])->name('lang.switch');

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');

Route::get('/debug-upload', [\App\Http\Controllers\DebugUploadController::class, 'show'])->middleware(['auth'])->name('debug.upload');
Route::post('/debug-upload', [\App\Http\Controllers\DebugUploadController::class, 'upload'])->middleware(['auth'])->name('debug.upload.post');

// Las rutas comodín como /{name} siempre deben ir al final
Route::get('/{name}/product/{id}', ShowProduct::class)->name('product-show');
Route::get('/{name}/cart', Cart::class)->name('catalogo.cart');
// JSON endpoint to return current cart item count for a catalog
Route::get('/{name}/cart-count', function($name){
	$catalogo = CatalogoModel::resolveByName($name);
	abort_unless($catalogo, 404, 'Catalogo no encontrado');
	$count = CartModel::findCurrent($catalogo->id)?->count ?? 0;
	return response()->json(['count' => $count]);
})->name('catalogo.cartCount');

// Endpoint to sync client-side cart with server (optimistic client sync)
Route::post('/{name}/cart-sync', function(Request $request, $name){
	$catalogo = CatalogoModel::resolveByName($name);
	abort_unless($catalogo, 404, 'Catalogo no encontrado');
	$cart = CartModel::current($catalogo->id);

	$payload = $request->json()->all();
	$raw = $payload['items'] ?? [];
	// Normalize to int keys => int qty
	$items = [];
	foreach ($raw as $k => $v) {
		$items[(int)$k] = (int)$v;
	}

	// Remove items not present in payload
	foreach ($cart->items()->get() as $ci) {
		$pid = $ci->product_id;
		if (! array_key_exists($pid, $items) || ($items[$pid] <= 0)) {
			$ci->delete();
		}
	}

	// Add/update incoming items
	foreach ($items as $pid => $qty) {
		$qty = (int) $qty;
		if ($qty <= 0) continue;
		$product = ProductModel::find($pid);
		if (! $product) continue;

		$existing = $cart->items()->where('product_id', $product->id)->first();
		if ($existing) {
			$existing->quantity = $qty;
			$existing->save();
		} else {
			// use Cart::addProduct to keep pricing logic
			$cart->addProduct($product, $qty);
		}
	}

	$cart->load('items.product');
	return response()->json(['count' => $cart->count, 'items' => $cart->items->map(function($i){ return ['product_id'=>$i->product_id,'quantity'=>$i->quantity]; })]);
})->name('catalogo.cartSync');

Route::post('/{name}/checkout', function (Request $request, $name) {
    $catalogo = CatalogoModel::resolveByName($name);
    abort_unless($catalogo, 404, 'Catalogo no encontrado');

    $cart = CartModel::current($catalogo->id)->load('items.product', 'items.variant');

    if ($cart->items->isEmpty()) {
        return response()->json(['message' => 'El carrito está vacío.'], 400);
    }

	$validated = $request->validate([
		'customer_name' => ['required', 'string', 'max:120'],
		'customer_phone' => ['required', 'string', 'max:40'],
		'customer_notes' => ['nullable', 'string', 'max:1000'],
	]);

    $message = "Pedido desde tucatalogo.lat\n\nHola me interesan estos productos:\n ";

    foreach ($cart->items as $item) {
        $variantText = '';
        if ($item->variant) {
            $variantText = " ({$item->variant->size} {$item->variant->color})";
        }

        $price = $item->product->precio_descuento ?? $item->product->price;
        if ($item->variant) {
            $price += $item->variant->price_adjustment;
        }

        $message .= "- {$item->product->name}{$variantText} x{$item->quantity} = $" . ($price * $item->quantity) . "\n";
    }

	$message .= "\nCliente: {$validated['customer_name']}\nTeléfono: {$validated['customer_phone']}";
	if (! empty($validated['customer_notes'])) {
		$message .= "\nNotas: {$validated['customer_notes']}";
	}

	$whatsappPhone = preg_replace('/\D+/', '', (string) $catalogo->telefono_contacto);
	if (str_starts_with($whatsappPhone, '00')) {
		$whatsappPhone = substr($whatsappPhone, 2);
	} elseif (str_starts_with($whatsappPhone, '0')) {
		$whatsappPhone = '58' . substr($whatsappPhone, 1);
	}
	if (strlen($whatsappPhone) < 10) {
		return response()->json(['message' => 'El catálogo no tiene un número de WhatsApp válido configurado.'], 422);
	}

    $encodedMessage = urlencode($message);
	$order = DB::transaction(function () use ($cart, $catalogo, $validated) {
		$total = $cart->items->sum(fn ($item) => $item->quantity * $item->price);
		$order = OrderModel::create([
			'catalogo_id' => $catalogo->id,
			'user_id' => auth()->id(),
			'session_id' => session()->getId(),
			'customer_name' => $validated['customer_name'],
			'customer_phone' => $validated['customer_phone'],
			'customer_notes' => $validated['customer_notes'] ?? null,
			'total' => $total,
			'status' => 'pending',
		]);

		foreach ($cart->items as $item) {
			$variantDescription = $item->variant
				? trim("{$item->variant->size} {$item->variant->color}")
				: null;

			$order->items()->create([
				'product_id' => $item->product_id,
				'variant_id' => $item->variant_id,
				'product_name' => $item->product?->name ?? 'Producto eliminado',
				'variant_description' => $variantDescription,
				'quantity' => $item->quantity,
				'unit_price' => $item->price,
				'total' => $item->quantity * $item->price,
			]);
		}

		return $order;
	});

	$whatsappUrl = "https://wa.me/{$whatsappPhone}?text={$encodedMessage}";

    $cart->items()->delete();

	return response()->json(['url' => $whatsappUrl, 'order_id' => $order->id]);
})->name('catalogo.checkout');

Route::get('/{name}', Catalogo::class)->name('catalogo');
