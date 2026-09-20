<div class="mx-auto max-w-7xl px-5 py-8 lg:px-8">
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Inventario</h1>
            <p class="mt-2 text-sm leading-6 text-slate-500">Consulta las últimas entradas y salidas de tus productos.</p>
        </div>
        <div class="flex flex-col gap-2 sm:flex-row">
            <a data-tour="inventory-entry" href="{{ route('inventory.entry') }}" wire:navigate class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-3 text-sm font-bold text-white hover:bg-indigo-700"><span class="text-lg leading-none">+</span> Cargar inventario</a>
            <a data-tour="inventory-exit" href="{{ route('inventory.exit') }}" wire:navigate class="inline-flex items-center justify-center gap-2 rounded-xl border border-rose-200 bg-white px-4 py-3 text-sm font-bold text-rose-700 hover:bg-rose-50"><span class="text-lg leading-none">−</span> Salida de inventario</a>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4 sm:px-6"><h2 class="text-lg font-bold text-slate-900">Últimos movimientos</h2></div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-3">Fecha</th><th class="px-5 py-3">Producto</th><th class="px-5 py-3">Variante</th><th class="px-5 py-3">Tipo</th><th class="px-5 py-3 text-right">Cantidad</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($movements as $movement)
                        <tr class="text-slate-700">
                            <td class="whitespace-nowrap px-5 py-4 text-slate-500">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-4 font-semibold text-slate-900">{{ $movement->product?->name ?? 'Producto eliminado' }}</td>
                            <td class="px-5 py-4">{{ $movement->variant_description ?: ($movement->variant ? trim(implode(' ', array_filter([$movement->variant->name ? $movement->variant->name . ':' : null, $movement->variant->size, $movement->variant->color]))) : 'General') }}</td>
                            <td class="px-5 py-4"><span class="inline-flex rounded-full px-2.5 py-1 text-xs font-bold {{ $movement->type === 'entry' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">{{ $movement->type === 'entry' ? 'Entrada' : 'Salida' }}</span></td>
                            <td class="px-5 py-4 text-right font-bold {{ $movement->type === 'entry' ? 'text-emerald-700' : 'text-rose-700' }}">{{ $movement->type === 'entry' ? '+' : '-' }}{{ $movement->quantity }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-slate-500">Todavía no hay movimientos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 px-5 py-4 sm:px-6">{{ $movements->links() }}</div>
    </div>
</div>
