<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Home;
use App\Livewire\Login;
use App\Livewire\Register;
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
use App\Http\Controllers\LanguageController;

Route::get('/', Home::class)->middleware(['guest'])->name('home');
Route::get('/Login', Login::class)->middleware(['guest'])->name('login');
Route::get('/Register', Register::class)->middleware(['guest'])->name('register');

Route::get('/Dashboard', Dashboard::class)->middleware(['auth'])->name('dashboard');
Route::get('/Products', Products::class)->middleware(['auth'])->name('products');
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

Route::get('/debug-upload', [\App\Http\Controllers\DebugUploadController::class, 'show'])->middleware(['auth'])->name('debug.upload');
Route::post('/debug-upload', [\App\Http\Controllers\DebugUploadController::class, 'upload'])->middleware(['auth'])->name('debug.upload.post');

// Las rutas comodín como /{name} siempre deben ir al final
Route::get('/{name}/product/{id}', ShowProduct::class)->name('product-show');
Route::get('/{name}/cart', Cart::class)->name('catalogo.cart');
// JSON endpoint to return current cart item count for a catalog
Route::get('/{name}/cart-count', function($name){
	$catalogo = \App\Models\Catalogo::where('name', $name)->firstOrFail();
	$count = \App\Models\Cart::findCurrent($catalogo->id)?->count ?? 0;
	return response()->json(['count' => $count]);
})->name('catalogo.cartCount');
Route::get('/{name}', Catalogo::class)->name('catalogo');