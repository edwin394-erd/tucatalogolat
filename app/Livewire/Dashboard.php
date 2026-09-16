<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Catalogo;
use App\Models\Order;
use App\Models\CatalogVisit;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Dashboard extends Component
{
    public $n_productos;
    public $n_categorias;
    public $catalogLink;

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
    public $n_pedidos = 0;
    public $n_pedidos_pendientes = 0;
    public $n_pedidos_ultimos_7_dias = 0;
    public $total_pedidos = 0;
    public $n_visitas = 0;
    public $n_visitas_ultimos_7_dias = 0;
    public $pedidos_recientes = [];
    public $visitas_recientes = [];
    public $plan_name;
    public $plan_expires_at;
    public $plan_days_remaining;
    public $plan_is_expired = false;



    public function render()
    {
        $subscription = auth()->user()->subscriptions()->with('plan')->latest()->first();
        $this->plan_name = $subscription?->plan?->name;
        $this->plan_expires_at = $subscription?->expires_at;
        $secondsRemaining = $subscription?->expires_at
            ? now()->diffInSeconds($subscription->expires_at, false)
            : null;
        $this->plan_is_expired = $secondsRemaining !== null && $secondsRemaining <= 0;
        $this->plan_days_remaining = $secondsRemaining === null
            ? null
            : (int) ceil(max(0, $secondsRemaining) / 86400);

        if (auth()->user()->catalogo) {
            $catalogo = Catalogo::find(auth()->user()->catalogo->id);
            $this->n_productos = $catalogo->products()->count();
            $this->n_categorias = $catalogo->categories()->count();
            $this->n_productos_ultimos_7_dias = $catalogo->products()->where('created_at', '>=', now()->subDays(7))->count();
            $this->n_categorias_ultimos_7_dias = $catalogo->categories()->where('created_at', '>=', now()->subDays(7))->count();
            $this->catalogLink = route('catalogo', $catalogo->name_handle);

            $orders = Order::where('catalogo_id', $catalogo->id);
            $visits = CatalogVisit::where('catalogo_id', $catalogo->id);
            $this->n_pedidos = (clone $orders)->count();
            $this->n_pedidos_pendientes = (clone $orders)->where('status', 'pending')->count();
            $this->n_pedidos_ultimos_7_dias = (clone $orders)->where('created_at', '>=', now()->subDays(7))->count();
            $this->total_pedidos = (float) (clone $orders)->where('status', 'completed')->sum('total');
            $this->n_visitas = (clone $visits)->count();
            $this->n_visitas_ultimos_7_dias = (clone $visits)->where('visited_at', '>=', now()->subDays(7))->count();
            $this->pedidos_recientes = (clone $orders)->latest()->limit(5)->get();
            $this->visitas_recientes = (clone $visits)->latest('visited_at')->limit(5)->get();
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
