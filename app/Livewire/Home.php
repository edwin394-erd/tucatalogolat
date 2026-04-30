<?php

namespace App\Livewire;
use App\Models\Catalogo;
use App\Models\Plan;
use Livewire\Component;

class Home extends Component
{
    
    public function render()
    {
        $plans = Plan::where('visibility', true)->get();

        return view('livewire.home')
        ->extends('layouts.guest')
        ->section('content')
        ->with('plans', $plans);
    }
}
