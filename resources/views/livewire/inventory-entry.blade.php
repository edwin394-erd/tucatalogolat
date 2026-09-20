<div class="mx-auto max-w-3xl px-5 py-8 lg:px-8">
    <x-alert alert_type="success" />
    <div class="mb-8"><a href="{{ $productId ? route('products.stock', ['id' => $productId]) : route('inventory') }}" wire:navigate class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">&larr; {{ $productId ? 'Volver al stock del producto' : 'Volver al inventario' }}</a><h1 class="mt-4 text-3xl font-bold tracking-tight text-slate-900">Cargar inventario</h1><p class="mt-2 text-sm text-slate-500">La cantidad ingresada se sumará al stock actual y quedará registrada como entrada.</p></div>
    @include('livewire.inventory-form', ['movementType' => 'entry'])
</div>