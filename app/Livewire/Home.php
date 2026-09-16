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

        $demos = [
            [
                'name' => 'Urban Style',
                'slug' => 'urbanstyle',
                'url' => 'https://tucatalogolat.lat/urbanstyle',
                'image' => asset('imgs/URBANSTYLE.png'),
            ],
            [
                'name' => 'Electronics Store',
                'slug' => 'electronicsstore',
                'url' => 'https://tucatalogolat.lat/electronicsstore',
                'image' => asset('imgs/electronicsstore.png'),
            ],
            [
                'name' => 'Auto Selling',
                'slug' => 'autoselling',
                'url' => 'https://tucatalogolat.lat/autoselling',
                'image' => asset('imgs/autoselling.png'),
            ],
            [
                'name' => 'Super Burger',
                'slug' => 'superburguer',
                'url' => 'https://tucatalogolat.lat/superburguer',
                'image' => asset('imgs/superburguers.png'),
            ],
        ];

        return view('livewire.home')
            ->extends('layouts.guest')
            ->section('content')
            ->with('plans', $plans)
            ->with('demos', $demos);
    }
}
