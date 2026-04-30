<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use App\Models\Product;
use App\Models\Catalogo;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
   public function boot(): void
{

    View::composer('*', function ($view) {
        $productsCount = 0;
        if (auth()->check() && auth()->user()->catalogo) {
            $productsCount = auth()->user()->catalogo->products()->count();
        }
        $view->with('productsCount', $productsCount);
    });
}
}
