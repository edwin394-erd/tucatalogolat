<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;

class CartBadge extends Component
{
    public $count = 0;
    public $catalogo;
    public $iconColor = '#ffffff';

    protected $listeners = [
        'cartUpdated' => 'handleCartUpdated',
        'refreshCartBadge' => 'refreshCount'
    ];

    public function mount()
    {
        $name = request()->route('name');
        if ($name) {
            $catalogo = \App\Models\Catalogo::resolveByName($name);
            $this->count = $catalogo ? (Cart::findCurrent($catalogo->id)?->count ?? 0) : 0;

            $primaryColor = $catalogo->theme->primary_color ?? '#000000';
            $this->iconColor = $this->isDarkColor($primaryColor) ? '#ffffff' : '#000000';
        }
    }

    private function isDarkColor($hex)
    {
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
        return (($r * 299 + $g * 587 + $b * 114) / 1000) < 128;
    }

    public function handleCartUpdated($count)
    {
        $this->count = (int) $count;
    }

    public function refreshCount()
    {
        $name = request()->route('name');
        if ($name) {
            $catalogo = \App\Models\Catalogo::resolveByName($name);
            $this->count = $catalogo ? (Cart::findCurrent($catalogo->id)?->count ?? 0) : 0;
        }
    }

    public function render()
    {
        return view('livewire.cart-badge');
    }
}