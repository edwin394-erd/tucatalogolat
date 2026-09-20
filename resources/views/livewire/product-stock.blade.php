<div x-data="{ openInventoryModal: @js(request()->query('open') === 'entry' ? 'entry' : null) }" class="mx-auto max-w-5xl px-5 py-8 lg:px-8">
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <a href="{{ route('products') }}" wire:navigate class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">&larr; Volver a productos</a>
            <h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-900">Stock de {{ $product->name }}</h1>
            <p class="mt-2 text-sm text-slate-500">Consulta todas las cantidades disponibles de este producto.</p>
        </div>
            <div class="flex flex-col items-stretch gap-3 sm:items-end">
                <div class="flex flex-col gap-2 sm:flex-row">
                    <button type="button" @click="openInventoryModal = 'entry'" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-3 text-sm font-bold text-white hover:bg-indigo-700">+ Cargar inventario</button>
                    <button type="button" @click="openInventoryModal = 'exit'" class="inline-flex items-center justify-center gap-2 rounded-xl border border-rose-200 bg-white px-4 py-3 text-sm font-bold text-rose-700 hover:bg-rose-50">− Salida de inventario</button>
                </div>
                @if ($product->variants->isNotEmpty())
                    <label class="inline-flex cursor-pointer items-center justify-end gap-2 text-sm font-semibold text-slate-700">
                        <input type="checkbox" wire:model.live="onlyAvailable" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        Solo disponibles
                    </label>
                @endif
            </div>
    </div>

    @if ($product->variants->isEmpty())
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-wide text-slate-500">Stock disponible</p>
            <p class="mt-2 text-4xl font-black text-indigo-600">{{ $product->stock }}</p>
            <p class="mt-1 text-sm text-slate-500">unidades</p>
        </div>
    @else
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4 sm:px-6"><h2 class="text-lg font-bold text-slate-900">Stock por variante</h2></div>
            <div class="overflow-x-auto">
                @if ($stockRows->isEmpty())
                    <div class="px-5 py-8 text-center text-sm text-slate-500">No hay variantes disponibles con el filtro actual.</div>
                @else
                <table wire:key="product-stock-table-{{ md5(implode('|', $variantColumns)) }}" class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr>@foreach ($variantColumns as $column)<th wire:key="stock-header-{{ md5($column) }}" class="px-5 py-3">{{ $column }}</th>@endforeach<th wire:key="stock-header-quantity" class="px-5 py-3 text-right">Cantidad disponible</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($stockRows as $row)
                            <tr wire:key="stock-row-{{ md5($row['selectionKey']) }}" class="text-slate-700 {{ $row['unassigned'] ? 'bg-amber-50' : '' }}">@foreach ($variantColumns as $column)<td wire:key="stock-cell-{{ md5($row['selectionKey'].'|'.$column) }}" class="px-5 py-4 font-semibold {{ $row['unassigned'] ? 'text-amber-900' : 'text-slate-900' }}">@php($cellValue = $row['values'][$column] ?? null)@if($row['unassigned'] && (! $cellValue || $cellValue === 'Pendiente de asignar'))<div class="inline-flex items-center gap-2"><select wire:change="setPendingSelection({{ $row['stockId'] }}, '{{ $column }}', $event.target.value, @js($row['values']))" class="rounded-lg border-amber-300 bg-white text-sm text-amber-900"><option value="">Pendiente de asignar</option>@foreach($columnOptions[$column] ?? [] as $option)<option value="{{ $option }}">{{ $option }}</option>@endforeach</select>@if(($row['lastPendingColumn'] ?? null) === $column)<input type="number" min="1" max="{{ $row['stock'] }}" wire:model.live="pendingQuantities.{{ $row['stockId'] }}" class="w-20 rounded-lg border-amber-300 bg-white text-sm text-amber-900" placeholder="Cantidad" aria-label="Cantidad a asignar"><input type="checkbox" wire:click="confirmPendingSelection({{ $row['stockId'] }})" class="h-5 w-5 rounded border-amber-400 text-amber-600 focus:ring-amber-500" title="Confirmar asignación" aria-label="Confirmar asignación">@endif</div>@else{{ $cellValue ?: '—' }}@endif</td>@endforeach<td wire:key="stock-cell-{{ md5($row['selectionKey'].'|quantity') }}" class="px-5 py-4 text-right text-lg font-black {{ $row['unassigned'] ? 'text-amber-700' : ($row['stock'] > 0 ? 'text-indigo-600' : 'text-rose-600') }}">{{ $row['stock'] }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    @endif
    <div x-show="openInventoryModal" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-180" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @keydown.escape.window="openInventoryModal = null" @inventory-movement-saved.window="openInventoryModal = null; $wire.$refresh()" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm" @click.self="openInventoryModal = null">
        <div x-show="openInventoryModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-2" class="max-h-[calc(100vh-2rem)] w-full max-w-2xl overflow-y-auto rounded-3xl bg-white shadow-2xl" @click.stop>
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 class="text-xl font-bold text-slate-900" x-text="openInventoryModal === 'entry' ? 'Cargar inventario' : 'Salida de inventario'"></h2>
                <button type="button" @click="openInventoryModal = null" class="rounded-full p-2 text-slate-400 hover:bg-slate-100" aria-label="Cerrar modal">&times;</button>
            </div>
            <div class="p-5">
                <div x-show="openInventoryModal === 'entry'">
                    @livewire('inventory-entry', ['product_id' => $product->id, 'modal' => true], key('stock-entry-modal-'.$product->id))
                </div>
                <div x-show="openInventoryModal === 'exit'">
                    @livewire('inventory-exit', ['product_id' => $product->id, 'modal' => true], key('stock-exit-modal-'.$product->id))
                </div>
            </div>
        </div>
    </div>
</div>