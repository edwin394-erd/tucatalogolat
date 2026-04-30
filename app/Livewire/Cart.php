<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart as CartModel;
use App\Models\Catalogo;

class Cart extends Component
{
    public $name;
    public $catalogo;
    public $cart;

    public function mount($name)
    {
        $this->name = $name;
        $this->catalogo = Catalogo::where('name', $name)->firstOrFail();
        $this->refreshCart();
    }

    public function refreshCart()
    {
        $this->cart = CartModel::current($this->catalogo->id)->load('items.product', 'items.variant');
    }

    public function removeItem($itemId)
    {
        $item = $this->cart->items()->find($itemId);

        if ($item) {
            $item->delete();
            session()->now('message', __('messages.item_removed'));
        }

        $this->refreshCart();
    }

    public function increaseQuantity($itemId)
    {
        $item = $this->cart->items()->find($itemId);

        if ($item) {
            $item->quantity += 1;
            $item->save();
            session()->now('message', __('messages.added_to_cart'));
        }

        $this->refreshCart();
    }

    public function decreaseQuantity($itemId)
    {
        $item = $this->cart->items()->find($itemId);

        if ($item && $item->quantity > 1) {
            $item->quantity -= 1;
            $item->save();
        }

        $this->refreshCart();
    }

    public function clearCart()
    {
        $this->cart->items()->delete();
        session()->now('message', __('messages.cart_cleared'));
        $this->refreshCart();
    }

    public function checkout()
    {
       
        $user = auth()->user();
        $message = "Pedido desde tucatalogo.lat\n\nHola me interesan estos productos:\n ";

        foreach ($this->cart->items as $item) {
            $variantText = '';
            if ($item->variant) {
                $variantText = " ({$item->variant->size} {$item->variant->color})";
            }
            $price = $item->product->precio_descuento ?? $item->product->price;
            if ($item->variant) {
                $price += $item->variant->price_adjustment;
            }
            $message .= "- {$item->product->name}{$variantText} x{$item->quantity} = $" . ($price * $item->quantity) . "\n";
        }

        $total = $this->cart->items->sum(function ($item) {
            return ($item->product->precio_descuento ?? $item->product->price) * $item->quantity;
        });

        $encodedMessage = urlencode($message);
        $whatsappUrl = "https://wa.me/584246054544?text={$encodedMessage}";

        // Clear cart after sending
        $this->cart->items()->delete();

        session()->flash('message', 'Pedido enviado a WhatsApp. Te contactaremos pronto.');
        return redirect($whatsappUrl);
    }

    public function render()
    {
        return view('livewire.cart', [
            'catalogo' => $this->catalogo,
            'cart' => $this->cart,
        ])->extends('layouts.catalogo1');
    }
}