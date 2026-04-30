<?php

namespace App\Livewire;

use Livewire\Component;

class Cuenta extends Component
{
    Public $correo;
    Public $telefono;
    Public $codigodearea;
    Public $plan;


    public function mount()
    {
        $user = auth()->user();
        $this->correo = auth()->user()->email;
        $this->telefono = auth()->user()->telephone;

        $this->plan = $user->subscriptions->last() ? $user->subscriptions->last()->plan->name : __('messages.no_plan');

        
    }

    public function render()
    {

    $catalogo = auth()->user()->catalogo;
    $user = auth()->user();
    $correo = $user->email;
    $telefono = $user->telephone;
    $plan = $user->subscriptions->last() ? $user->subscriptions->last()->plan->name : __('messages.no_plan');
        return view('livewire.cuenta', compact('correo', 'telefono', 'plan'))
            ->extends('layouts.auth2')
            ->section('content');

    }
}
