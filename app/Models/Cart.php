<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\InventoryStock;

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

    public function addProduct(Product $product, int $quantity = 1, $variantId = null, array $variantSelections = [])
    {
        $variantSelections = array_values(array_unique(array_map('intval', $variantSelections)));
        if (empty($variantSelections) && $variantId) {
            $variantSelections = [(int) $variantId];
        }
        sort($variantSelections);

        if (! $this->canAddProduct($product, $quantity, $variantSelections)) {
            return false;
        }

        $variantDescription = $this->buildVariantDescription($variantSelections);
        $item = $this->items()
            ->where('product_id', $product->id)
            ->where('variant_id', $variantId)
            ->get()
            ->first(fn ($candidate) => $this->sameVariantSelections($candidate->variant_selections, $variantSelections));

        if ($item) {
            $item->quantity += $quantity;
            $item->save();
        } else {
            $price = $product->precio_descuento ?? $product->price;
            foreach ($variantSelections as $selectionId) {
                $variant = $product->variants()->find($selectionId);
                if ($variant) {
                    $price += $variant->price_adjustment;
                }
            }
            $this->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price,
                'variant_id' => $variantId,
                'variant_selections' => $variantSelections ?: null,
                'variant_description' => $variantDescription,
            ]);
        }

        $this->load('items.product');

        return true;
    }

    public function canAddProduct(Product $product, int $quantity, array $variantSelections = []): bool
    {
        $availableStock = $this->availableStock($product, $variantSelections);
        if ($availableStock === null) {
            return true;
        }

        $existingQuantity = $this->items()
            ->where('product_id', $product->id)
            ->get()
            ->filter(fn ($item) => $this->sameVariantSelections($item->variant_selections, $variantSelections))
            ->sum('quantity');

        return $existingQuantity + $quantity <= $availableStock;
    }

    public function availableStock(Product $product, array $variantSelections = []): ?int
    {
        if (! $product->manage_stock) {
            return null;
        }

        if ($product->variants()->exists()) {
            if (empty($variantSelections)) {
                return 0;
            }

            $variants = $product->variants()->whereIn('id', $variantSelections)->get();
            if ($variants->count() !== count($variantSelections) || $variants->contains(fn ($variant) => ! $variant->available)) {
                return 0;
            }

            $selectionKey = InventoryStock::selectionKey($variants);
            $combinationStock = InventoryStock::where('product_id', $product->id)
                ->where('selection_key', $selectionKey)
                ->value('stock');

            return $combinationStock === null ? 0 : (int) $combinationStock;
        }

        return (int) $product->stock;
    }

    private function sameVariantSelections(?array $stored, array $selected): bool
    {
        $stored = array_values(array_unique(array_map('intval', $stored ?? [])));
        sort($stored);

        return $stored === $selected;
    }

    private function buildVariantDescription(array $selectionIds): ?string
    {
        if (empty($selectionIds)) {
            return null;
        }

        $variants = ProductVariant::whereIn('id', $selectionIds)->get();

        return $variants->map(function ($variant) {
            $value = $variant->name
                ? ($variant->size ?: $variant->color)
                : trim($variant->size . ' ' . $variant->color);

            return trim(($variant->name ? $variant->name . ': ' : '') . $value);
        })->filter()->unique()->implode(' / ') ?: null;
    }
}