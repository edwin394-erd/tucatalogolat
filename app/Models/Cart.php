<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'catalogo_id',
        'session_id',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function catalogo()
    {
        return $this->belongsTo(Catalogo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalAttribute()
    {
        return $this->items->sum(fn($item) => $item->quantity * $item->price);
    }

    public function getCountAttribute()
    {
        return $this->items->sum('quantity');
    }

    public static function current(int $catalogoId)
    {
        $sessionId = session()->getId();

        $query = static::where('catalogo_id', $catalogoId)
            ->where(function ($query) use ($sessionId) {
                $query->where('session_id', $sessionId);

                if (auth()->check()) {
                    $query->orWhere('user_id', auth()->id());
                }
            });

        $cart = $query->first();

        if (! $cart) {
            $cart = static::create([
                'catalogo_id' => $catalogoId,
                'session_id' => $sessionId,
                'user_id' => auth()->id(),
                'status' => 'open',
            ]);
        } elseif (! $cart->user_id && auth()->check()) {
            $cart->update(['user_id' => auth()->id()]);
        }

        return $cart;
    }

    public static function findCurrent(int $catalogoId)
    {
        $sessionId = session()->getId();

        return static::where('catalogo_id', $catalogoId)
            ->where(function ($query) use ($sessionId) {
                $query->where('session_id', $sessionId);

                if (auth()->check()) {
                    $query->orWhere('user_id', auth()->id());
                }
            })
            ->first();
    }

    public function addProduct(Product $product, int $quantity = 1, $variantId = null)
    {
        $item = $this->items()->where('product_id', $product->id)->where('variant_id', $variantId)->first();

        if ($item) {
            $item->quantity += $quantity;
            $item->save();
        } else {
            $price = $product->precio_descuento ?? $product->price;
            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                if ($variant) {
                    $price += $variant->price_adjustment;
                }
            }
            $this->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price,
                'variant_id' => $variantId,
            ]);
        }

        $this->load('items.product');
    }
}