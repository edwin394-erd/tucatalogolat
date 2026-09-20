<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\InventoryStock;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ProductStock extends Component
{
    public Product $product;
    public bool $onlyAvailable = true;
    public array $pendingSelections = [];
    public array $pendingQuantities = [];

    public function setPendingSelection(int $stockId, string $column, string $value, array $knownValues = []): void
    {
        foreach ($knownValues as $knownColumn => $knownValue) {
            if (filled($knownValue) && $knownValue !== 'Pendiente de asignar') {
                $this->pendingSelections[$stockId][$knownColumn] = $knownValue;
            }
        }
        $this->pendingSelections[$stockId][$column] = $value;
    }

    public function confirmPendingSelection(int $stockId): void
    {
        $selected = $this->pendingSelections[$stockId] ?? [];
        $columns = $this->variantColumns();
        $quantity = (int) ($this->pendingQuantities[$stockId] ?? 0);

        $source = InventoryStock::where('product_id', $this->product->id)->find($stockId);
        if (! $source || $quantity < 1 || $quantity > (int) $source->stock) {
            session()->flash('message', 'Indica una cantidad válida disponible para asignar.');
            return;
        }

        if (collect($columns)->every(fn ($requiredColumn) => filled($selected[$requiredColumn] ?? null))) {
            $target = $this->currentTargetRows()->first(fn ($row) => collect($columns)->every(
                fn ($requiredColumn) => ($row['values'][$requiredColumn] ?? '') === $selected[$requiredColumn]
            ));

            if ($target) {
                $this->assignPendingStock($stockId, $target['selectionKey'], $target['selectionIds'], $quantity);
                if ($quantity >= (int) $source->stock) {
                    unset($this->pendingSelections[$stockId], $this->pendingQuantities[$stockId]);
                } else {
                    $this->pendingQuantities[$stockId] = (int) $source->stock - $quantity;
                }
            }
        }
    }

    public function assignPendingStock(int $sourceId, string $targetKey, array $targetVariantSelections = [], int $quantity = 0): void
    {
        $source = InventoryStock::where('product_id', $this->product->id)->findOrFail($sourceId);
        $target = InventoryStock::where('product_id', $this->product->id)
            ->get()
            ->first(fn ($stock) => $this->normalizeSelectionKey($stock->selection_key) === $this->normalizeSelectionKey($targetKey));

        if ($source->id === $target?->id) {
            return;
        }

        $quantity = $quantity > 0 ? min($quantity, (int) $source->stock) : (int) $source->stock;

        DB::transaction(function () use ($source, $target, $targetKey, $targetVariantSelections, $quantity) {
            if (! $target) {
                $target = InventoryStock::create([
                    'catalogo_id' => $this->product->catalogo_id,
                    'product_id' => $this->product->id,
                    'selection_key' => $targetKey,
                    'variant_selections' => $targetVariantSelections,
                    'stock' => 0,
                ]);
            }

            $target->increment('stock', $quantity);
            if ($quantity >= (int) $source->stock) {
                $source->delete();
            } else {
                $source->decrement('stock', $quantity);
            }
        });

        session()->flash('message', 'Stock pendiente asignado correctamente.');
    }

    public function mount(int $id): void
    {
        $this->product = Product::with('variants')
            ->where('catalogo_id', auth()->user()->catalogo?->id)
            ->findOrFail($id);

        if ($this->product->variants->isEmpty()) {
            $variantStock = (int) InventoryStock::where('product_id', $this->product->id)->sum('stock');

            if ($variantStock > 0) {
                $this->product->increment('stock', $variantStock);
                InventoryStock::where('product_id', $this->product->id)->delete();
                $this->product->refresh();
            }
        }
    }

    public function render()
    {
        $standardVariants = $this->product->variants->filter(fn ($variant) => blank($variant->name));
        $customGroups = $this->product->variants
            ->filter(fn ($variant) => filled($variant->name))
            ->groupBy(fn ($variant) => $this->normalizeColumnName($variant->name))
            ->map(fn ($variants, $name) => [
                'name' => $name,
                'variants' => $variants->unique(fn ($variant) => $variant->size ?: $variant->color)->values(),
            ])->values();

        $variantColumns = $this->variantColumnsFor($standardVariants, $customGroups);
        $uniqueColumns = [];
        foreach ($variantColumns as $column) {
            $column = $this->normalizeColumnName((string) $column);
            $columnKey = $this->normalizeColumnKey($column);
            if ($column !== '' && ! array_key_exists($columnKey, $uniqueColumns)) {
                $uniqueColumns[$columnKey] = $column;
            }
        }
        $variantColumns = array_values($uniqueColumns);

        $standardRows = $standardVariants
            ->unique(fn ($variant) => implode('|', [$variant->size, $variant->color]))
            ->values();

        $rows = $standardRows->isNotEmpty()
            ? $standardRows->map(fn ($variant) => collect([$variant]))
            : collect([collect()]);

        foreach ($customGroups as $group) {
            $rows = $rows->flatMap(function ($selectedVariants) use ($group) {
                return $group['variants']->map(fn ($variant) => $selectedVariants->concat([$variant]));
            })->values();
        }

        $combinationStocks = InventoryStock::where('product_id', $this->product->id)
            ->get();
        $historicalMovements = InventoryMovement::where('product_id', $this->product->id)
            ->where('type', 'entry')
            ->latest()
            ->get();

        $stockRows = $rows->map(function ($variants) use ($variantColumns) {
            $values = [];
            $standard = $variants->first(fn ($variant) => blank($variant->name));

            if ($standard && filled($standard->size)) {
                $values['Talla'] = $standard->size;
            }
            if ($standard && filled($standard->color)) {
                $values['Color'] = $standard->color;
            }
            foreach ($variants->filter(fn ($variant) => filled($variant->name)) as $customVariant) {
                $values[$this->normalizeColumnName($customVariant->name)] = $customVariant->size ?: $customVariant->color;
            }

            $pendingColumns = collect($variantColumns)
                ->filter(fn ($column) => blank($values[$column] ?? null))
                ->values();

            return [
                'values' => $values,
                'stock' => $variants->isEmpty()
                    ? (int) $this->product->stock
                    : 0,
                'available' => $variants->every(fn ($variant) => (bool) $variant->available),
                'selectionKey' => $this->normalizeSelectionKey(InventoryStock::selectionKey($variants)),
                'selectionIds' => $variants->pluck('id')->values()->all(),
                'lastPendingColumn' => $pendingColumns->last(),
            ];
        });

        $stockRows = $stockRows->map(function ($row) use ($combinationStocks) {
            $matchingStocks = $combinationStocks->filter(fn ($stock) =>
                $this->normalizeSelectionKey($stock->selection_key) === $row['selectionKey']
            );
            if ($matchingStocks->isNotEmpty()) {
                $row['stock'] = (int) $matchingStocks->sum('stock');
            }

            return $row;
        });

        $currentKeys = $stockRows->pluck('selectionKey')->all();
        $unassignedRows = $combinationStocks
            ->filter(fn ($stock) => $stock->stock > 0 && ! in_array($this->normalizeSelectionKey($stock->selection_key), $currentKeys, true))
            ->map(function ($stock) use ($variantColumns, $standardVariants, $customGroups, $historicalMovements) {
                $values = [];
                $tokens = preg_split('/\|+/', $stock->selection_key, -1, PREG_SPLIT_NO_EMPTY);

                foreach ($standardVariants->pluck('size')->filter()->unique() as $size) {
                    if (in_array((string) $size, $tokens, true)) {
                        $values['Talla'] = $size;
                    }
                }
                foreach ($standardVariants->pluck('color')->filter()->unique() as $color) {
                    if (in_array((string) $color, $tokens, true)) {
                        $values['Color'] = $color;
                    }
                }
                foreach ($customGroups as $group) {
                    foreach ($group['variants'] as $variant) {
                        $value = $variant->size ?: $variant->color;
                        if (in_array((string) $value, $tokens, true)) {
                            $values[$group['name']] = $value;
                            break;
                        }
                    }
                }

                $movement = $historicalMovements->first(fn ($movement) =>
                    (int) $movement->quantity === (int) $stock->stock
                    && filled($movement->variant_description)
                );
                if ($movement) {
                    foreach (explode(' / ', $movement->variant_description) as $part) {
                        [$name, $value] = array_pad(explode(': ', $part, 2), 2, null);
                        if ($value !== null && in_array($name, $variantColumns, true)) {
                            $values[$name] = $value;
                        } elseif ($value === null && in_array('Talla', $variantColumns, true)) {
                            foreach ($standardVariants->pluck('size')->filter() as $size) {
                                if (str_starts_with($part, $size . ' ')) {
                                    $values['Talla'] = $size;
                                    $remaining = trim(substr($part, strlen($size)));
                                    if ($remaining && in_array($remaining, $standardVariants->pluck('color')->filter()->all(), true)) {
                                        $values['Color'] = $remaining;
                                    }
                                }
                            }
                        }
                    }
                }

                foreach ($variantColumns as $column) {
                    $values[$column] ??= 'Pendiente de asignar';
                }

                return [
                    'values' => $values,
                    'stock' => (int) $stock->stock,
                    'available' => true,
                    'selectionKey' => $stock->selection_key,
                    'stockId' => $stock->id,
                    'lastPendingColumn' => collect($variantColumns)->filter(fn ($column) => ($values[$column] ?? null) === 'Pendiente de asignar')->last(),
                    'unassigned' => true,
                ];
            });

        $unassignedRows = $unassignedRows->values();

        // A deleted and recreated variant can have a different historical key
        // while still representing the same visible combination. Reconcile it
        // with the current row instead of showing complete data as pending.
        $unassignedRows = $unassignedRows->reject(function ($pendingRow) use (&$stockRows, $variantColumns) {
            $pendingValues = collect($variantColumns)
                ->mapWithKeys(fn ($column) => [$column => $pendingRow['values'][$column] ?? null]);

            if ($pendingValues->contains(fn ($value) => blank($value) || $value === 'Pendiente de asignar')) {
                return false;
            }

            $currentRow = $stockRows->search(function ($row) use ($pendingValues, $variantColumns) {
                return collect($variantColumns)->every(fn ($column) =>
                    ($row['values'][$column] ?? null) === $pendingValues[$column]
                );
            });

            if ($currentRow === false) {
                return false;
            }

            $currentStockRow = $stockRows->get($currentRow);
            $currentStockRow['stock'] += $pendingRow['stock'];
            $stockRows->put($currentRow, $currentStockRow);
            return true;
        })->values();

        $allStockRows = $stockRows->map(function ($row) use ($combinationStocks) {
            $row['stockId'] = $combinationStocks
                ->first(fn ($stock) => $this->normalizeSelectionKey($stock->selection_key) === $row['selectionKey'])?->id;
            return $row + ['unassigned' => false];
        })->concat($unassignedRows);

        $assignableRows = $allStockRows->filter(fn ($row) => ! $row['unassigned'])->values();
        $stockRows = $this->onlyAvailable
            ? $allStockRows->filter(fn ($row) => $row['stock'] > 0)->values()
            : $allStockRows;

        $columnOptions = collect($variantColumns)->mapWithKeys(fn ($column) => [
            $column => $assignableRows->pluck('values.' . $column)->filter()->unique()->values(),
        ]);

        return view('livewire.product-stock', compact('variantColumns', 'stockRows', 'assignableRows', 'columnOptions'))
            ->extends('layouts.auth2')
            ->section('content');
    }

    private function normalizeSelectionKey(string $key): string
    {
        return trim((string) preg_replace('/\|+/', '|', $key), '|');
    }

    private function variantColumns(): array
    {
        $standardVariants = $this->product->variants->filter(fn ($variant) => blank($variant->name));
        $customGroups = $this->product->variants
            ->filter(fn ($variant) => filled($variant->name))
            ->groupBy(fn ($variant) => $this->normalizeColumnName($variant->name))
            ->map(fn ($variants, $name) => ['name' => $name]);

        return $this->variantColumnsFor($standardVariants, $customGroups);
    }

    private function variantColumnsFor($standardVariants, $customGroups): array
    {
        $columns = $standardVariants->contains(fn ($variant) => filled($variant->size)) ? ['Talla'] : [];
        if ($standardVariants->contains(fn ($variant) => filled($variant->color))) $columns[] = 'Color';

        $seen = [];
        foreach ($customGroups->pluck('name') as $name) {
            $normalizedName = $this->normalizeColumnName($name);
            if ($normalizedName === '') {
                continue;
            }

            $columnKey = mb_strtolower($normalizedName);
            if (! isset($seen[$columnKey])) {
                $seen[$columnKey] = true;
                $columns[] = $normalizedName;
            }
        }

        return $columns;
    }

    private function currentTargetRows()
    {
        $standard = $this->product->variants->filter(fn ($variant) => blank($variant->name))->unique(fn ($variant) => implode('|', [$variant->size, $variant->color]));
        $groups = $this->product->variants
            ->filter(fn ($variant) => filled($variant->name))
            ->groupBy(fn ($variant) => $this->normalizeColumnName($variant->name))
            ->map(fn ($variants) => $variants->unique(fn ($variant) => $variant->size ?: $variant->color)->values());
        $rows = $standard->isNotEmpty() ? $standard->map(fn ($variant) => collect([$variant])) : collect([collect()]);
        foreach ($groups as $options) $rows = $rows->flatMap(fn ($selected) => $options->map(fn ($option) => $selected->concat([$option])))->values();
        return $rows->map(function ($variants) {
            $values = [];
            $base = $variants->first(fn ($variant) => blank($variant->name));
            if ($base?->size) $values['Talla'] = $base->size;
            if ($base?->color) $values['Color'] = $base->color;
            foreach ($variants->filter(fn ($variant) => filled($variant->name)) as $variant) $values[$this->normalizeColumnName($variant->name)] = $variant->size ?: $variant->color;
            return [
                'values' => $values,
                'selectionKey' => InventoryStock::selectionKey($variants),
                'selectionIds' => $variants->pluck('id')->values()->all(),
            ];
        });
    }

    private function normalizeColumnName(string $name): string
    {
        $name = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $name) ?? $name;
        $name = preg_replace('/\s+/u', ' ', trim($name)) ?? trim($name);

        return $name;
    }

    private function normalizeColumnKey(string $name): string
    {
        return mb_strtolower(preg_replace('/[^\p{L}\p{N}]+/u', '', $name) ?? $name);
    }
}