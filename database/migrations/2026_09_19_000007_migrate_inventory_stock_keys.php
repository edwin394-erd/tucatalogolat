<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('inventory_stocks')->orderBy('id')->each(function ($stock) {
            $variantIds = json_decode($stock->variant_selections ?: '[]', true) ?: [];
            $variants = DB::table('product_variants')
                ->whereIn('id', array_map('intval', $variantIds))
                ->get();

            if ($variants->isEmpty()) {
                return;
            }

            $key = $variants
                ->map(fn ($variant) => implode('|', [
                    trim((string) $variant->name),
                    trim((string) $variant->size),
                    trim((string) $variant->color),
                ]))
                ->sort()
                ->values()
                ->implode('||');

            $existing = DB::table('inventory_stocks')
                ->where('product_id', $stock->product_id)
                ->where('selection_key', $key)
                ->where('id', '!=', $stock->id)
                ->first();

            if ($existing) {
                DB::table('inventory_stocks')->where('id', $existing->id)->update([
                    'stock' => $existing->stock + $stock->stock,
                    'updated_at' => now(),
                ]);
                DB::table('inventory_stocks')->where('id', $stock->id)->delete();
                return;
            }

            DB::table('inventory_stocks')->where('id', $stock->id)->update([
                'selection_key' => $key,
                'updated_at' => now(),
            ]);
        });
    }

    public function down(): void
    {
        // The original ID-based keys cannot be reconstructed after this migration.
    }
};