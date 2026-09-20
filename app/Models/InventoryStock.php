<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryStock extends Model
{
    protected $fillable = [
        'catalogo_id',
        'product_id',
        'selection_key',
        'variant_selections',
        'stock',
    ];

    protected $casts = [
        'variant_selections' => 'array',
    ];

    public static function selectionKey(iterable $variants): string
    {
        $parts = collect($variants)
            ->map(fn ($variant) => implode('|', [
                trim((string) $variant->name),
                trim((string) $variant->size),
                trim((string) $variant->color),
            ]))
            ->sort()
            ->values()
            ->all();

        return $parts ? implode('||', $parts) : 'base';
    }
}