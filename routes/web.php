<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Home;
use App\Livewire\Login;
use App\Livewire\Register;
use App\Livewire\ForgotPassword;
use App\Livewire\ResetPassword;
use App\Livewire\Dashboard;
use App\Livewire\Products;
use App\Livewire\Inventory;
use App\Livewire\InventoryEntry;
use App\Livewire\InventoryExit;
use App\Livewire\ProductStock;
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
use App\Models\InventoryMovement;
use App\Models\InventoryStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\TelegramWebhookController;

Route::get('/', Home::class)->middleware(['guest'])->name('home');
Route::get('/Login', Login::class)->middleware(['guest'])->name('login');
Route::get('/Register', Register::class)->middleware(['guest'])->name('register');
Route::get('/forgot-password', ForgotPassword::class)->middleware(['guest'])->name('password.request');
Route::get('/reset-password/{token}', ResetPassword::class)->middleware(['guest'])->name('password.reset');
Route::view('/terminos-y-condiciones', 'legal.terms')->name('terms');

Route::get('/Dashboard', Dashboard::class)->middleware(['auth'])->name('dashboard');
Route::get('/Products', Products::class)->middleware(['auth'])->name('products');
Route::get('/Products/{id}/Stock', ProductStock::class)->middleware(['auth'])->name('products.stock');
Route::get('/Inventory', Inventory::class)->middleware(['auth'])->name('inventory');
Route::get('/Inventory/Entry/{product_id?}', InventoryEntry::class)->middleware(['auth'])->name('inventory.entry');
Route::get('/Inventory/Exit/{product_id?}', InventoryExit::class)->middleware(['auth'])->name('inventory.exit');
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

Route::post('/telegram/webhook', TelegramWebhookController::class)->name('telegram.webhook');

Route::get('/debug-upload', [\App\Http\Controllers\DebugUploadController::class, 'show'])->middleware(['auth'])->name('debug.upload');
Route::post('/debug-upload', [\App\Http\Controllers\DebugUploadController::class, 'upload'])->middleware(['auth'])->name('debug.upload.post');

// Las rutas comodín como /{name} siempre deben ir al final
Route::get('/{name}/product/{id}', ShowProduct::class)->name('product-show');
Route::get('/{name}/product/{id}/variant-stock', function ($name, $id) {
	$catalogo = CatalogoModel::resolveByName($name);
	abort_unless($catalogo, 404, 'Catalogo no encontrado');

	$product = ProductModel::where('catalogo_id', $catalogo->id)
		->with('variants:id,product_id,available')
		->findOrFail($id);

	if (! $product->manage_stock) {
		return response()->json(['manage_stock' => false]);
	}

	if ($product->variants->isEmpty()) {
		return response()->json([
			'manage_stock' => true,
			'product_stock' => (int) $product->stock,
			'combinations' => [],
		]);
	}

	$combinations = InventoryStock::where('product_id', $product->id)
		->get(['variant_selections', 'stock'])
		->map(fn ($stock) => [
			'variant_selections' => array_values(array_map('intval', $stock->variant_selections ?? [])),
			'stock' => (int) $stock->stock,
		])
		->values();

	return response()->json([
		'manage_stock' => true,
		'product_stock' => null,
		'combinations' => $combinations,
	]);
})->name('catalogo.variantStock');

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
	$rawProducts = $payload['products'] ?? [];
	$rawVariants = $payload['variants'] ?? [];
	$rawSelections = $payload['selections'] ?? [];
	$items = [];
	$products = [];
	if ($rawProducts) {
		foreach ($rawProducts as $lineKey => $productId) {
			$products[(string) $lineKey] = (int) $productId;
			$items[(string) $lineKey] = (int) ($raw[$lineKey] ?? 0);
		}
	} else {
		foreach ($raw as $productId => $quantity) {
			$lineKey = (string) $productId;
			$products[$lineKey] = (int) $productId;
			$items[$lineKey] = (int) $quantity;
		}
	}
	$variants = [];
	$selections = [];
	foreach ($rawVariants as $lineKey => $variantId) {
		$variants[(string) $lineKey] = $variantId ? (int) $variantId : null;
	}
	foreach ($rawSelections as $lineKey => $selectionIds) {
		$selections[(string) $lineKey] = array_values(array_unique(array_map('intval', (array) $selectionIds)));
		sort($selections[(string) $lineKey]);
	}

	// Remove items not present in payload
	foreach ($cart->items()->get() as $ci) {
		$lineExists = false;
		foreach ($products as $lineKey => $productId) {
			$storedSelections = array_values(array_unique(array_map('intval', $ci->variant_selections ?? [])));
			sort($storedSelections);
			if ($productId === $ci->product_id && (int) ($variants[$lineKey] ?? null) === (int) $ci->variant_id && ($selections[$lineKey] ?? []) === $storedSelections && ($items[$lineKey] ?? 0) > 0) {
				$lineExists = true;
				break;
			}
		}
		if (! $lineExists) {
			$ci->delete();
		}
	}

	// Add/update incoming items
	foreach ($items as $lineKey => $qty) {
		$qty = (int) $qty;
		if ($qty <= 0) continue;
		$pid = $products[$lineKey] ?? null;
		if (! $pid) continue;
		$product = ProductModel::find($pid);
		if (! $product) continue;
		$variantId = $variants[$lineKey] ?? null;
		$selectionIds = $selections[$lineKey] ?? ($variantId ? [$variantId] : []);
		$selectionVariants = $product->variants()->whereIn('id', $selectionIds)->get();
		if (count($selectionIds) !== $selectionVariants->count()) continue;
		$variant = $variantId ? $product->variants()->whereKey($variantId)->first() : null;
		if ($variantId && (! $variant || ! $variant->available)) continue;
		if ($selectionVariants->contains(fn ($selectedVariant) => ! $selectedVariant->available)) continue;

		$existing = $cart->items()->where('product_id', $product->id)->where('variant_id', $variantId)->get()->first(function ($candidate) use ($selectionIds) {
			$stored = array_values(array_unique(array_map('intval', $candidate->variant_selections ?? [])));
			sort($stored);
			return $stored === $selectionIds;
		});
		if ($existing) {
			$availableStock = $cart->availableStock($product, $selectionIds);
			if ($availableStock !== null && $qty > $availableStock) continue;
			$existing->quantity = $qty;
			$existing->save();
		} else {
			// use Cart::addProduct to keep pricing logic
			$cart->addProduct($product, $qty, $variantId, $selectionIds);
		}
	}

	$cart->load('items.product');
	return response()->json(['count' => $cart->count, 'items' => $cart->items->map(function($i){
		return [
			'line_key' => $i->product_id . ':' . implode('-', $i->variant_selections ?: [$i->variant_id ?: '0']),
			'product_id' => $i->product_id,
			'variant_id' => $i->variant_id,
			'variant_selections' => $i->variant_selections,
			'variant_description' => $i->variant_description,
			'quantity' => $i->quantity,
		];
	})]);
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
			$variantText = ' (' . ($item->variant_description ?: trim(($item->variant->name ? $item->variant->name . ': ' : '') . $item->variant->size . ' ' . $item->variant->color)) . ')';
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

	foreach ($cart->items as $item) {
		if (! $item->product || ! $item->product->manage_stock) continue;

		$selectionIds = array_values(array_unique(array_map('intval', $item->variant_selections ?? [])));
		if (empty($selectionIds) && $item->variant_id) $selectionIds = [(int) $item->variant_id];
		$availableStock = $cart->availableStock($item->product, $selectionIds);
		if ($item->quantity > $availableStock) {
			return response()->json(['message' => "No hay suficiente stock para {$item->product->name}."], 422);
		}
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
			$variantDescription = $item->variant_description ?: ($item->variant
				? trim(($item->variant->name ? $item->variant->name . ': ' : '') . $item->variant->size . ' ' . $item->variant->color)
				: null);

			$order->items()->create([
				'product_id' => $item->product_id,
				'variant_id' => $item->variant_id,
				'product_name' => $item->product?->name ?? 'Producto eliminado',
				'variant_description' => $variantDescription,
				'quantity' => $item->quantity,
				'unit_price' => $item->price,
				'total' => $item->quantity * $item->price,
			]);

			$selectionIds = array_values(array_unique(array_map('intval', $item->variant_selections ?? [])));
			if (empty($selectionIds) && $item->variant_id) {
				$selectionIds = [(int) $item->variant_id];
			}

			if ($item->product?->manage_stock && ! empty($selectionIds)) {
				$selectedVariants = collect();
				foreach ($selectionIds as $selectionId) {
					$variant = $item->product?->variants()->whereKey($selectionId)->first();
					if (! $variant) {
						continue;
					}

					$variant->decrement('stock', $item->quantity);
					$selectedVariants->push($variant);
				}

				if ($selectedVariants->isNotEmpty()) {
					$selectionKey = InventoryStock::selectionKey($selectedVariants);
					$inventoryStock = InventoryStock::where('product_id', $item->product_id)
						->where('selection_key', $selectionKey)
						->lockForUpdate()
						->first();
					if ($inventoryStock) {
						$inventoryStock->decrement('stock', $item->quantity);
					}

					InventoryMovement::create([
						'catalogo_id' => $catalogo->id,
						'product_id' => $item->product_id,
						'variant_id' => $selectedVariants->first()->id,
						'variant_description' => $selectedVariants->map(fn ($variant) => trim(implode(' ', array_filter([$variant->name ? $variant->name . ':' : null, $variant->size, $variant->color]))))->implode(' / '),
						'type' => 'exit',
						'quantity' => $item->quantity,
						'note' => 'Salida por pedido',
						'reference_type' => OrderModel::class,
						'reference_id' => $order->id,
					]);
				}
			} elseif ($item->product?->manage_stock) {
				$item->product->decrement('stock', $item->quantity);
				InventoryMovement::create([
					'catalogo_id' => $catalogo->id,
					'product_id' => $item->product_id,
					'type' => 'exit',
					'quantity' => $item->quantity,
					'note' => 'Salida por pedido',
					'reference_type' => OrderModel::class,
					'reference_id' => $order->id,
				]);
			}
		}

		return $order;
	});

	$whatsappUrl = "https://wa.me/{$whatsappPhone}?text={$encodedMessage}";

    $cart->items()->delete();

	return response()->json(['url' => $whatsappUrl, 'order_id' => $order->id]);
})->name('catalogo.checkout');

Route::get('/{name}', Catalogo::class)->name('catalogo');
