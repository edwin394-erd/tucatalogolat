<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;

class CartBadge extends Component
{
    public $count = 0;

    protected $listeners = [
        'cartUpdated' => 'handleCartUpdated',
        'refreshCartBadge' => 'refreshCount'
    ];

    public function mount()
    {
        $name = request()->route('name');
        if ($name) {
            $catalogo = \App\Models\Catalogo::where('name', $name)->first();
            $this->count = $catalogo ? (Cart::findCurrent($catalogo->id)?->count ?? 0) : 0;
        }
    }

    public function handleCartUpdated($count)
    {
        $this->count = (int) $count;
    }

    public function refreshCount()
    {
        $name = request()->route('name');
        if ($name) {
            $catalogo = \App\Models\Catalogo::where('name', $name)->first();
            $this->count = $catalogo ? (Cart::findCurrent($catalogo->id)?->count ?? 0) : 0;
        }
    }

    public function render()
    {
        return view('livewire.cart-badge');
    }
}
