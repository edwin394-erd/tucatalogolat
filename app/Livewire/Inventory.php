<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\InventoryMovement;
use App\Models\InventoryStock;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Inventory extends Component
{
    use WithPagination;

    public $productId = '';
    public $variantId = '';
    public $size = '';
    public $color = '';
    public $customVariantSelections = [];
    public $quantity = 0;
    public bool $modal = false;

    public function mount(?int $product_id = null, bool $modal = false): void
    {
        $this->productId = (string) ($product_id ?? request()->query('product_id', ''));
        $this->modal = $modal;
    }

    public function rules(): array
    {
        return [
            'productId' => ['required', 'integer', 'exists:products,id'],
            'variantId' => ['nullable', 'integer', 'exists:product_variants,id'],
            'size' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:50'],
            'customVariantSelections' => ['array'],
            'customVariantSelections.*' => ['nullable', 'integer', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function updatedProductId(): void
    {
        $this->variantId = '';
        $this->size = '';
        $this->color = '';
        $this->customVariantSelections = [];
        $this->quantity = 0;
        $this->resetValidation();
    }

    public function updatedSize(): void
    {
        $this->resetMovementSelection();
    }

    public function updatedColor(): void
    {
        $this->resetMovementSelection();
    }

    public function updatedCustomVariantSelections(): void
    {
        $this->resetMovementSelection();
    }

    public function save(): void
    {
        $this->recordMovement('entry');
    }

    protected function recordMovement(string $type): void
    {
        $this->validate();

        $product = $this->catalogProducts()->find($this->productId);
        if (! $product) {
            $this->addError('productId', 'El producto seleccionado no pertenece a tu catálogo.');
            return;
        }

        if ($product->variants()->exists()) {
            $variants = $this->resolveVariants($product);
            if ($variants->isEmpty()) {
                $this->addError('variantId', 'Completa la talla, el color o el valor personalizado para cargar su stock.');
                return;
            }

            $selectionIds = $variants->pluck('id')->sort()->values()->all();
            $inventoryStock = InventoryStock::firstOrCreate([
                'product_id' => $product->id,
                'selection_key' => InventoryStock::selectionKey($variants),
            ], [
                'catalogo_id' => $product->catalogo_id,
                'variant_selections' => $selectionIds,
                'stock' => 0,
            ]);

            if ($type === 'exit' && $inventoryStock->stock < $this->quantity) {
                $this->addError('quantity', 'La cantidad supera el stock disponible de una de las variantes seleccionadas.');
                return;
            }

            DB::transaction(function () use ($product, $variants, $type, $inventoryStock) {
                if ($type === 'entry' && ! $product->manage_stock) {
                    $product->update(['manage_stock' => true]);
                }

                $type === 'entry'
                    ? $inventoryStock->increment('stock', (int) $this->quantity)
                    : $inventoryStock->decrement('stock', (int) $this->quantity);

                InventoryMovement::create([
                    'catalogo_id' => $product->catalogo_id,
                    'product_id' => $product->id,
                    'variant_id' => $variants->first()->id,
                    'variant_description' => $variants->map(fn ($variant) => $this->variantLabel($variant))->implode(' / '),
                    'user_id' => auth()->id(),
                    'type' => $type,
                    'quantity' => (int) $this->quantity,
                    'note' => $type === 'entry' ? 'Carga manual de inventario' : 'Salida manual de inventario',
                ]);
            });
            $label = $variants->map(fn ($variant) => $this->variantLabel($variant))->implode(', ');
        } else {
            if ($this->variantId !== '' && $this->variantId !== null) {
                $this->addError('variantId', 'Este producto no tiene variantes.');
                return;
            }

            if ($type === 'exit' && $product->stock < $this->quantity) {
                $this->addError('quantity', 'La cantidad supera el stock disponible del producto.');
                return;
            }

            DB::transaction(function () use ($product, $type) {
                if ($type === 'entry' && ! $product->manage_stock) {
                    $product->update(['manage_stock' => true]);
                }

                $type === 'entry'
                    ? $product->increment('stock', (int) $this->quantity)
                    : $product->decrement('stock', (int) $this->quantity);
                InventoryMovement::create([
                    'catalogo_id' => $product->catalogo_id,
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'type' => $type,
                    'quantity' => (int) $this->quantity,
                    'note' => $type === 'entry' ? 'Carga manual de inventario' : 'Salida manual de inventario',
                ]);
            });
            $label = $product->name;
        }

        $movementMessage = ($type === 'entry' ? 'Entrada' : 'Salida') . " de {$this->quantity} unidades registrada para {$label}.";
        if (! $this->modal) {
            session()->flash('message', $movementMessage);
        }
        $this->reset(['variantId', 'size', 'color', 'customVariantSelections', 'quantity']);
        $this->dispatch('inventory-movement-saved', message: $movementMessage);
    }

    public function render()
    {
        return view('livewire.inventory', [
            'movements' => InventoryMovement::with(['product', 'variant'])
                ->where('catalogo_id', auth()->user()->catalogo?->id)
                ->latest()
                ->paginate(15),
        ])->extends('layouts.auth2')->section('content');
    }

    protected function formData(): array
    {
        $products = $this->catalogProducts()
            ->where('manage_stock', true)
            ->with('variants')
            ->orderBy('name')
            ->get();
        $selectedProduct = $products->firstWhere('id', (int) $this->productId);
        $productVariants = $selectedProduct?->variants ?? collect();
        $standardVariants = $productVariants->filter(fn ($variant) => blank($variant->name));
        $customGroups = $productVariants->filter(fn ($variant) => filled($variant->name))
            ->groupBy('name')
            ->map(fn ($variants, $name) => [
                'name' => $name,
                'options' => $variants->values(),
            ])->values();

        $currentStock = null;
        if ($selectedProduct) {
            if ($selectedProduct->variants->isEmpty()) {
                $currentStock = (int) $selectedProduct->stock;
            } else {
                $selectedVariants = $this->resolveVariants($selectedProduct);
                if ($selectedVariants->isNotEmpty()) {
                    $selectionKey = InventoryStock::selectionKey($selectedVariants);
                    $currentStock = (int) InventoryStock::where('product_id', $selectedProduct->id)
                        ->where('selection_key', $selectionKey)
                        ->value('stock');
                }
            }
        }

        return [
            'products' => $products,
            'sizeOptions' => $standardVariants->pluck('size')->filter()->unique()->values(),
            'colorOptions' => $standardVariants->pluck('color')->filter()->unique()->values(),
            'customGroups' => $customGroups,
            'currentStock' => $currentStock,
        ];
    }

    protected function catalogProducts()
    {
        return Product::query()->where('catalogo_id', auth()->user()->catalogo?->id);
    }

    protected function variantLabel(ProductVariant $variant): string
    {
        return trim(implode(' ', array_filter([
            $variant->name ? $variant->name . ':' : null,
            $variant->size,
            $variant->color,
        ]))) ?: 'Variante';
    }

    protected function resolveVariants(Product $product)
    {
        $resolved = collect();
        $standardVariants = $product->variants()->where(function ($query) {
            $query->whereNull('name')->orWhere('name', '');
        });

        if ($standardVariants->exists()) {
            $hasSizes = $product->variants()->where(function ($query) {
                $query->whereNull('name')->orWhere('name', '');
            })->whereNotNull('size')->where('size', '!=', '')->exists();
            $hasColors = $product->variants()->where(function ($query) {
                $query->whereNull('name')->orWhere('name', '');
            })->whereNotNull('color')->where('color', '!=', '')->exists();

            if (($hasSizes && blank($this->size)) || ($hasColors && blank($this->color))) {
                return collect();
            }

            $standardVariant = $standardVariants
                ->when(filled($this->size), fn ($query) => $query->where('size', $this->size))
                ->when(filled($this->color), fn ($query) => $query->where('color', $this->color))
                ->first();

            if (! $standardVariant) {
                return collect();
            }

            $resolved->push($standardVariant);
        }

        $customVariants = $product->variants()->whereNotNull('name')->where('name', '!=', '')->get();
        foreach ($customVariants->groupBy('name')->values() as $groupIndex => $groupVariants) {
            $selectedId = $this->customVariantSelections[$groupIndex] ?? null;

            if (! $selectedId || ! $groupVariants->contains('id', (int) $selectedId)) {
                return collect();
            }

            $resolved->push($groupVariants->firstWhere('id', (int) $selectedId));
        }

        return $resolved;
    }

    protected function resetMovementSelection(): void
    {
        $this->variantId = '';
        $this->quantity = 0;
        $this->resetValidation();
    }
}