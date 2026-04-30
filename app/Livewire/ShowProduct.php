<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product; // Asegúrate de importar tu modelo

class ShowProduct extends Component
{
    public $product;
    public $name;
    public $selectedVariantId = null;

    public function mount($name,$id)
    {
        // Buscamos el producto por ID. Si no existe, lanza un error 404.
         $id_catalogo = \App\Models\Catalogo::where('name', $name)->firstOrFail()->id;

        // debug rápido: ver valores

        $this->product = Product::where('catalogo_id', $id_catalogo)
        ->where('id', $id)->first();
       

        if (! $this->product) {
            abort(404, "Producto no encontrado: id={$id} catalogo_id={$id_catalogo}");
        }

        $this->name = $name;
    }

    public function addToCart($productId)
    {
        $catalogo = \App\Models\Catalogo::where('name', $this->name)->firstOrFail();
        $product = Product::where('catalogo_id', $catalogo->id)->findOrFail($productId);

        $cart = \App\Models\Cart::current($catalogo->id);
        $cart->addProduct($product, 1, $this->selectedVariantId);

        session()->now('message', __('messages.added_to_cart'));
    }

    public function render()
    {
        return view('livewire.show-product')
            ->extends('layouts.guest')
            ->section('content')
            ->with('name',$this->name);

    }
}