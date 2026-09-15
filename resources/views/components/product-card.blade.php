<div x-data="{ showProductModal: false }" class="rounded-2xl md:rounded-3xl shadow-sm hover:shadow-xl overflow-hidden flex flex-col group border border-black/5 bg-[var(--bg-card-aside)]">

    {{-- Imagen --}}
    <div class="relative w-full aspect-square overflow-hidden">
        @if(!empty($item->fotos) && isset($item->fotos[0]))
            <img src="{{ asset('storage/' . $item->fotos[0]->url) }}"
                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                 loading="lazy" alt="{{ $item->name }}">
        @else
            <div class="absolute inset-0 flex items-center justify-center text-xs opacity-50" style="color: var(--text-secondary);">Sin imagen</div>
        @endif
    </div>

    {{-- Contenido --}}
    <div class="p-3 md:p-4 flex flex-col flex-1 min-w-0">
        <h5 class="text-sm md:text-base font-bold mb-1 truncate" style="color: var(--text-primary);">
            {{ $item->name }}
        </h5>

        <p class="text-xs md:text-sm mb-3 flex-1 line-clamp-2 leading-relaxed" style="color: var(--text-primary); opacity: 0.75;">
            {{ $item->description }}
        </p>

        {{-- Precio: siempre en su propia fila, nunca compite por espacio con los botones --}}
        <div class="flex items-baseline gap-2 mb-3 min-w-0">
            @if($item->precio_descuento)
                <span class="text-lg md:text-xl font-black truncate" style="color: var(--text-primary);">
                    ${{ number_format($item->precio_descuento, 2) }}
                </span>
                <span class="text-xs font-semibold opacity-50 line-through truncate" style="color: var(--text-primary);">
                    ${{ number_format($item->price, 2) }}
                </span>
            @else
                <span class="text-lg md:text-xl font-black truncate" style="color: var(--text-primary);">
                    ${{ number_format($item->price, 2) }}
                </span>
            @endif
        </div>

        {{-- Acciones: botones circulares de tamaño fijo, solo ícono. Nunca dependen del ancho disponible. --}}
        <div class="flex items-center justify-between gap-2 mt-auto">
            <button type="button" @click="showProductModal = true" title="Ver detalles"
                    class="shrink-0 w-10 h-10 rounded-full inline-flex items-center justify-center border transition hover:opacity-80 active:scale-95"
                    style="border-color: var(--primary-btn); color: var(--primary-btn);">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12.01" y2="8" />
                    <line x1="12" y1="12" x2="12" y2="16" />
                </svg>
            </button>

            <div x-data class="shrink-0">
                <template x-if="(Alpine.store('cart') && (Alpine.store('cart').items['{{ $item->id }}'] || 0)) == 0">
                    <button type="button" x-on:click="window.cartAdd({{ $item->id }})" title="Agregar al carrito"
                            class="w-10 h-10 rounded-full text-white inline-flex items-center justify-center shadow-md transition hover:shadow-lg active:scale-95"
                            style="background-color: var(--primary-btn);">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                            <path d="M6 6h15l-1.68 9.39a2 2 0 0 1-1.99 1.61H8.31a2 2 0 0 1-1.99-1.61L4.57 4H2" />
                            <circle cx="9" cy="20" r="1" />
                            <circle cx="16" cy="20" r="1" />
                        </svg>
                    </button>
                </template>
                <template x-if="(Alpine.store('cart') && (Alpine.store('cart').items['{{ $item->id }}'] || 0)) > 0">
                    <div class="flex items-center gap-1 rounded-full border border-black/10 bg-white/40 p-1 shadow-inner">
                        <button type="button" @click="window.cartDecrease({{ $item->id }})" class="w-8 h-8 rounded-full bg-white/60 text-sm font-semibold text-[var(--text-primary)]">-</button>
                        <div class="w-6 text-center text-xs font-semibold text-[var(--text-primary)]" x-text="Alpine.store('cart') ? (Alpine.store('cart').items['{{ $item->id }}'] || 0) : 0"></div>
                        <button type="button" @click="window.cartIncrease({{ $item->id }})" class="w-8 h-8 rounded-full text-sm font-semibold text-white" style="background-color: var(--primary-btn);">+</button>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- Modal (sin cambios de fondo, solo limpieza menor) --}}
    <div x-show="showProductModal" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
        <div @click.away="showProductModal = false" @keydown.escape.window="showProductModal = false" class="flex max-h-[90vh] w-full max-w-xl flex-col overflow-hidden rounded-3xl bg-[var(--bg-card-aside)] text-[var(--text-secondary)] shadow-2xl">

            <header class="flex items-center justify-between border-b border-black/10 px-6 py-4">
                <div class="min-w-0">
                    <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[var(--text-secondary)]">Producto</span>
                    <h3 class="text-xl font-bold text-[var(--text-primary)] truncate">{{ $item->name }}</h3>
                </div>
                <button type="button" @click="showProductModal = false" class="shrink-0 flex h-9 w-9 items-center justify-center rounded-full text-xl font-bold transition hover:bg-black/5" aria-label="Cerrar">&times;</button>
            </header>

            <main class="grid grid-cols-1 flex-1 gap-6 overflow-y-auto p-6 sm:grid-cols-[180px_1fr]">
                <div class="relative mx-auto w-full max-w-[150px] h-[150px] overflow-hidden rounded-2xl bg-[var(--bg-main)] shadow-inner sm:mx-0 sm:max-w-none sm:h-auto sm:aspect-square">
                    @if(!empty($item->fotos) && isset($item->fotos[0]))
                        <img src="{{ asset('storage/' . $item->fotos[0]->url) }}" alt="{{ $item->name }}" class="w-full h-full object-cover" loading="lazy">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-xs opacity-50">Sin imagen</div>
                    @endif
                </div>

                <div class="flex flex-col justify-between gap-4 min-w-0">
                    <div class="space-y-4">
                        <div>
                            <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[var(--text-secondary)]">Precio</span>
                            <p class="text-2xl font-black text-[var(--text-primary)]">
                                @if($item->precio_descuento)
                                    ${{ number_format($item->precio_descuento, 2) }}
                                    <span class="ml-2 text-sm font-normal line-through text-[var(--text-secondary)]">${{ number_format($item->price, 2) }}</span>
                                @else
                                    ${{ number_format($item->price, 2) }}
                                @endif
                            </p>
                        </div>

                        <div class="space-y-1">
                            <span class="text-xs font-semibold text-[var(--text-primary)]">Descripción</span>
                            <p class="text-sm leading-relaxed text-[var(--text-secondary)]">{{ $item->description }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3 text-xs">
                            @if(!empty($item->category_id) || !empty($item->categoria))
                                <div class="rounded-xl bg-black/5 p-3 min-w-0">
                                    <span class="block font-semibold text-[var(--text-secondary)]">Categoría</span>
                                    <span class="mt-1 block font-medium text-[var(--text-primary)] truncate">{{ $item->category->name ?? $item->categoria ?? 'Sin categoría' }}</span>
                                </div>
                            @endif
                            @if(!empty($item->stock))
                                <div class="rounded-xl bg-black/5 p-3">
                                    <span class="block font-semibold text-[var(--text-secondary)]">Stock</span>
                                    <span class="mt-1 block font-medium text-[var(--text-primary)]">{{ $item->stock }} disponibles</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </main>

            <footer class="flex items-center justify-end gap-3 border-t border-black/10 px-6 py-4">
                <button type="button" x-data="{ anim:false }" @click="anim = true; window.cartAdd({{ $item->id }}); setTimeout(() => anim = false, 350)" wire:click="addToCart({{ $item->id }})" :class="anim ? 'scale-105 shadow-2xl ring-4 ring-white/20' : ''" class="rounded-xl bg-[var(--primary-btn)] px-5 py-2 text-sm font-semibold text-white transition transform duration-200 ease-out hover:scale-105 active:scale-95">Agregar al carrito</button>
            </footer>
        </div>
    </div>
</div>