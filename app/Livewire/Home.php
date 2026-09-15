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
                'image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'name' => 'Electronics Store',
                'slug' => 'electorinicsstore',
                'url' => 'https://tucatalogolat.lat/electorinicsstore',
                'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'name' => 'Auto Selling',
                'slug' => 'autoselling',
                'url' => 'https://tucatalogolat.lat/autoselling',
                'image' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'name' => 'Super Burger',
                'slug' => 'superburguer',
                'url' => 'https://tucatalogolat.lat/superburguer',
                'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=900&q=80',
            ],
        ];

        return view('livewire.home')
            ->extends('layouts.guest')
            ->section('content')
            ->with('plans', $plans)
            ->with('demos', $demos);
    }
}
