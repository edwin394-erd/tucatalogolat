<?php

namespace App\Livewire;

use Livewire\Component;

class Planes extends Component
{
    public $model;

    public function subscribe($planId)
    {
        $plan = \App\Models\Plan::find($planId);
        if (!$plan) {
            $this->dispatch('alert', type: 'error', message: 'Plan no encontrado.');
            return;
        }

        $user = auth()->user();
        if (!$user) {
            $this->dispatch('alert', type: 'error', message: 'Usuario no autenticado.');
            return;
        }

        $currentSubscription = $user->subscriptions()
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->latest('expires_at')
            ->first();

        if ($currentSubscription?->plan_id === $plan->id) {
            $this->dispatch('alert', type: 'info', message: 'Ya tienes este plan activo.');
            return;
        }

        $message = "Solicitud de suscripción al plan {$plan->name}:\n\nUsuario: {$user->name} ({$user->email})\nPlan: {$plan->name}\nPrecio: {$plan->price}\nDescripción: {$plan->description}";

        $encodedMessage = urlencode($message);
        $whatsappUrl = "https://wa.me/584246054544?text={$encodedMessage}";

        $this->dispatch('alert', type: 'success', message: 'Solicitud enviada a WhatsApp. Te contactaremos pronto.');
        $this->js("window.location.href = '$whatsappUrl';");
    }

    public function hola()
    {
        session()->flash('message', 'Hola desde Livewire!');
        return redirect()->route('planes');
    }
    
    public function render()
    {
        $currentSubscription = auth()->user()?->subscriptions()
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->latest('expires_at')
            ->first();

        return view('livewire.planes')
        ->extends('layouts.auth2')
        ->section('content')
        ->with('model', $this->model)
        ->with('currentSubscription', $currentSubscription);
    }
}
