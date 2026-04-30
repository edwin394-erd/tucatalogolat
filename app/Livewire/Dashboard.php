<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Catalogo;

class Dashboard extends Component
{
    public $n_productos;
    public $n_categorias;

    public $n_usuarios;
    public $n_planes;
    public $n_suscripciones;
    public $n_suscripciones_activas;
    public $n_suscripciones_expiradas;
    public $n_suscripciones_pendientes;
    public $n_productos_ultimos_7_dias;
    public $n_categorias_ultimos_7_dias;
    public $n_usuarios_ultimos_7_dias;
    public $n_suscripciones_ultimos_7_dias;
    public $n_suscripciones_activas_ultimos_7_dias;
    public $n_suscripciones_expiradas_ultimos_7_dias; 



    public function render()
    {
        if (auth()->user()->catalogo) {
          
            $this->n_productos = Catalogo::find(auth()->user()->catalogo->id)->products()->count();
            $this->n_categorias = Catalogo::find(auth()->user()->catalogo->id)->categories()->count();
            $this->n_productos_ultimos_7_dias = Catalogo::find(auth()->user()->catalogo->id)->products()->where('created_at', '>=', now()->subDays(7))->count();
            $this->n_categorias_ultimos_7_dias = Catalogo::find(auth()->user()->catalogo->id)->categories()->where('created_at', '>=', now()->subDays(7))->count();
        }

        $this->n_usuarios = \App\Models\User::count();
        $this->n_planes = \App\Models\Plan::count();
        $this->n_suscripciones = \App\Models\Subscription::count();
        $this->n_suscripciones_activas = \App\Models\Subscription::where('status', 'active')->count();
        $this->n_suscripciones_expiradas = \App\Models\Subscription::where('status', 'expired')->count();
        $this->n_suscripciones_pendientes = \App\Models\Subscription::where('status', 'pending')->count();
       
        $this->n_usuarios_ultimos_7_dias = \App\Models\User::where('created_at', '>=', now()->subDays(7))->count();
        $this->n_suscripciones_ultimos_7_dias = \App\Models\Subscription::where('created_at', '>=', now()->subDays(7))->count();
        $this->n_suscripciones_activas_ultimos_7_dias = \App\Models\Subscription::where('status', 'active')->where('created_at', '>=', now()->subDays(7))->count();
        $this->n_suscripciones_expiradas_ultimos_7_dias = \App\Models\Subscription::where('status', 'expired')->where('created_at', '>=', now()->subDays(7))->count();

        
        return view('livewire.dashboard')
        ->extends('layouts.auth2')
        ->section('content');
    }
}
