<form wire:submit="save" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    @php($selectedProduct = $products->firstWhere('id', (int) $productId))
    <div>
        <label class="mb-2 block text-sm font-bold text-slate-800">Producto</label>
        <div
            x-data="{
                open: false,
                filter: '',
                selectedId: @entangle('productId'),
                items: @js($products->map(function ($product) {
                    return [
                        'id' => (string) $product->id,
                        'label' => $product->name . ($product->variants->isEmpty() ? ' (' . $product->stock . ' disponibles)' : ''),
                    ];
                })->values()->all()),
                get filteredItems() {
                    const term = this.filter.trim().toLowerCase();
                    if (!term) return this.items;
                    return this.items.filter(item => item.label.toLowerCase().includes(term));
                },
                get selectedLabel() {
                    const item = this.items.find(item => String(item.id) === String(this.selectedId));
                    return item ? item.label : 'Selecciona un producto';
                },
                watchSelection() {
                    if (this.selectedId !== null && this.selectedId !== '') {
                        this.$wire.set('productId', this.selectedId);
                    }
                }
            }"
            x-init="$watch('selectedId', value => watchSelection())"
            x-on:click.away="open = false"
            class="relative"
        >
            <button type="button" x-on:click="open = !open; if (open) { $nextTick(() => $refs.productSearch.focus()); }" class="flex w-full items-center justify-between rounded-xl border border-slate-300 bg-slate-50 px-3 py-3 text-left text-sm text-slate-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                <span x-text="selectedLabel" :class="selectedId ? 'text-slate-900' : 'text-slate-400'" class="truncate"></span>
                <svg class="h-4 w-4 shrink-0 text-slate-500" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/></svg>
            </button>

            <div x-show="open" x-transition class="absolute z-20 mt-2 w-full overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl" style="display: none;">
                <div class="border-b border-slate-200 bg-slate-50 p-2">
                    <input x-ref="productSearch" x-model.debounce.200ms="filter" type="search" placeholder="Buscar producto..." class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>
                <ul class="max-h-64 overflow-y-auto">
                    <template x-if="filteredItems.length === 0">
                        <li class="px-4 py-3 text-sm text-slate-500">No se encontraron productos con stock.</li>
                    </template>
                    <template x-for="item in filteredItems" :key="item.id">
                        <li>
                            <button type="button" x-on:click="selectedId = item.id; open = false; filter = ''; $nextTick(() => watchSelection());" class="flex w-full items-center justify-between px-4 py-3 text-left text-sm text-slate-700 transition hover:bg-indigo-50" :class="String(item.id) === String(selectedId) ? 'bg-indigo-50 font-semibold text-indigo-700' : ''">
                                <span x-text="item.label"></span>
                                <template x-if="String(item.id) === String(selectedId)">
                                    <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-indigo-600" aria-hidden="true"><path fill-rule="evenodd" d="M16.704 4.296a1 1 0 0 1 0 1.414l-7.071 7.071a1 1 0 0 1-1.414 0L3.296 9.91a1 1 0 1 1 1.414-1.414l4.243 4.243 6.364-6.364a1 1 0 0 1 1.414 0Z" clip-rule="evenodd"/></svg>
                                </template>
                            </button>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
        @error('productId') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror
    </div>
    @if ($productId && $sizeOptions->isNotEmpty())<div><label for="inventory-size" class="mb-2 block text-sm font-bold text-slate-800">Talla</label><select id="inventory-size" wire:model.live="size" class="block w-full rounded-xl border-slate-300 bg-slate-50 p-3 text-sm"><option value="">Selecciona una talla</option>@foreach ($sizeOptions as $option)<option value="{{ $option }}">{{ $option }}</option>@endforeach</select></div>@endif
    @if ($productId && $colorOptions->isNotEmpty())<div><label for="inventory-color" class="mb-2 block text-sm font-bold text-slate-800">Color</label><select id="inventory-color" wire:model.live="color" class="block w-full rounded-xl border-slate-300 bg-slate-50 p-3 text-sm"><option value="">Selecciona un color</option>@foreach ($colorOptions as $option)<option value="{{ $option }}">{{ $option }}</option>@endforeach</select></div>@endif
    @foreach ($customGroups as $groupIndex => $group)<div><label for="inventory-custom-{{ $groupIndex }}" class="mb-2 block text-sm font-bold text-slate-800">{{ $group['name'] }}</label><select id="inventory-custom-{{ $groupIndex }}" wire:model.live="customVariantSelections.{{ $groupIndex }}" class="block w-full rounded-xl border-slate-300 bg-slate-50 p-3 text-sm"><option value="">Selecciona un valor</option>@foreach ($group['options'] as $variant)<option value="{{ $variant->id }}">{{ trim($variant->size ?: $variant->color) ?: 'Valor' }}</option>@endforeach</select></div>@endforeach
    @error('variantId') <span class="block text-sm text-rose-600">{{ $message }}</span> @enderror
    @if ($currentStock !== null)<p class="rounded-xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700">Stock actual de esta combinación: <span class="text-indigo-700">{{ $currentStock }}</span></p>@endif
    <div><label for="inventory-quantity" class="mb-2 block text-sm font-bold text-slate-800">Cantidad</label><input id="inventory-quantity" type="number" min="1" step="1" wire:model="quantity" class="block w-full rounded-xl border-slate-300 bg-slate-50 p-3 text-sm">@error('quantity') <span class="mt-1 block text-sm text-rose-600">{{ $message }}</span> @enderror</div>
    <div class="flex justify-end"><button type="submit" wire:loading.attr="disabled" class="inline-flex items-center gap-2 rounded-xl {{ $movementType === 'entry' ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-rose-600 hover:bg-rose-700' }} px-5 py-3 text-sm font-bold text-white"><span>{{ $movementType === 'entry' ? 'Registrar entrada' : 'Registrar salida' }}</span></button></div>
</form>