<div x-data="{ showProductModal: false }" class="rounded-2xl md:rounded-3xl shadow-sm hover:shadow-xl overflow-hidden flex flex-col group border border-black/5 bg-[var(--bg-card-aside)]">
    {{-- Imagen: proporción 1:1 en móvil sin ocupar todo el ancho --}}
    <div class="relative mx-auto w-full max-w-[18rem] aspect-square overflow-hidden rounded-[1.5rem] sm:max-w-full sm:rounded-[2rem]">
        <img src="{{ asset('storage/' . $item->fotos[0]->url) }}" 
             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
             loading="lazy" alt="{{ $item->name }}">
    </div>

    {{-- Contenido: Padding reducido en móvil --}}
    <div class="p-3 md:p-6 flex flex-col flex-1">
        <h5 class="text-sm md:text-lg font-bold mb-1 md:mb-2 line-clamp-1" style="color: var(--text-primary);">
            {{ $item->name }}
        </h5>

        {{-- Descripción: limitada a una sola línea --}}
        <p class="text-sm mb-6 flex-1 truncate leading-relaxed" style="color: var(--text-primary);">
            {{ $item->description }}
        </p>

        <div class="flex flex-col sm:flex-row items-end justify-between mt-auto gap-2 w-full">
            <div class="w-full sm:w-auto flex flex-col gap-1 text-right sm:text-left">
                @if($item->precio_descuento)
                    <span class="text-sm md:text-base font-black opacity-50 line-through truncate" style="color: var(--text-primary);">
                        ${{ number_format($item->price, 2) }}
                    </span>
                    <span class="text-xl md:text-2xl font-black truncate" style="color: var(--text-primary);">
                        ${{ number_format($item->precio_descuento, 2) }}
                    </span>
                @else
                    <span class="text-xl md:text-2xl font-black truncate" style="color: var(--text-primary);">
                        ${{ number_format($item->price, 2) }}
                    </span>
                @endif
            </div>

            {{-- Botones: móvil con texto, escritorio solo iconos y contador compacto --}}
            <div class="flex flex-col sm:flex-row items-center justify-end gap-2 w-full sm:w-auto">
                <button type="button" @click="showProductModal = true" class="w-full sm:w-auto p-3 rounded-2xl text-white inline-flex items-center justify-center gap-2 shadow-lg transition hover:shadow-xl active:scale-95" style="background-color: var(--primary-btn);">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12.01" y2="8" />
                        <line x1="12" y1="12" x2="12" y2="16" />
                    </svg>
                    <span class="text-sm font-semibold lg:hidden">Detalles</span>
                </button>

                <div x-data class="w-full sm:w-auto">
                    <template x-if="(Alpine.store('cart') && (Alpine.store('cart').items['{{ $item->id }}'] || 0)) == 0">
                        <button type="button" x-on:click="window.cartAdd({{ $item->id }})" class="w-full sm:w-auto p-3 rounded-2xl text-white inline-flex items-center justify-center gap-2 shadow-lg transition hover:shadow-xl active:scale-95" style="background-color: var(--primary-btn);">
                            <svg height="20" width="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                                <path d="M6 6h15l-1.68 9.39a2 2 0 0 1-1.99 1.61H8.31a2 2 0 0 1-1.99-1.61L4.57 4H2" />
                                <circle cx="9" cy="20" r="1" />
                                <circle cx="16" cy="20" r="1" />
                            </svg>
                            <span class="text-sm font-semibold lg:hidden">Agregar</span>
                        </button>
                    </template>
                    <template x-if="(Alpine.store('cart') && (Alpine.store('cart').items['{{ $item->id }}'] || 0)) > 0">
                        <div class="flex items-center justify-between rounded-2xl border border-black/10 bg-white/10 p-1 shadow-inner w-full sm:w-auto">
                            <button type="button" @click="window.cartDecrease({{ $item->id }})" class="w-9 h-9 lg:w-8 lg:h-8 rounded-2xl bg-white/10 text-sm font-semibold text-[var(--text-primary)]">-</button>
                            <div class="w-8 text-center text-sm font-semibold text-[var(--text-primary)] lg:text-xs" x-text="Alpine.store('cart') ? (Alpine.store('cart').items['{{ $item->id }}'] || 0) : 0"></div>
                            <button type="button" @click="window.cartIncrease({{ $item->id }})" class="w-9 h-9 lg:w-8 lg:h-8 rounded-2xl bg-[var(--primary-btn)] text-sm font-semibold text-white">+</button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

   <div x-show="showProductModal" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
    <div @click.away="showProductModal = false" @keydown.escape.window="showProductModal = false" class="flex max-h-[90vh] w-full max-w-xl flex-col overflow-hidden rounded-3xl bg-[var(--bg-card-aside)] text-[var(--text-secondary)] shadow-2xl">
        
        <!-- Header -->
        <header class="flex items-center justify-between border-b border-black/10 px-6 py-4 dark:border-white/10">
            <div>
                <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[var(--text-secondary)]">Producto</span>
                <h3 class="text-xl font-bold text-[var(--text-primary)]">{{ $item->name }}</h3>
            </div>
            <button type="button" @click="showProductModal = false" class="flex h-9 w-9 items-center justify-center rounded-full text-xl font-bold transition hover:bg-black/5 dark:hover:bg-white/10" aria-label="Cerrar">&times;</button>
        </header>

        <!-- Body / Content -->
        <main class="grid grid-cols-1 flex-1 gap-6 overflow-y-auto p-6 sm:grid-cols-[180px_1fr]">
            <!-- Imagen -->
            <div class="relative mx-auto w-full max-w-[150px] h-[150px] overflow-hidden rounded-2xl bg-[var(--bg-main)] shadow-inner sm:mx-0 sm:max-w-none sm:h-auto sm:aspect-square">
                @if(!empty($item->fotos) && isset($item->fotos[0]))
                    <img src="{{ asset('storage/' . $item->fotos[0]->url) }}" alt="{{ $item->name }}" class="w-full h-full object-cover" loading="lazy">
                @else
                    <div class="flex h-full w-full items-center justify-center text-xs opacity-50">Sin imagen</div>
                @endif
            </div>

            <!-- Información -->
            <div class="flex flex-col justify-between gap-4">
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
                            <div class="rounded-xl bg-black/5 p-3 dark:bg-white/5">
                                <span class="block font-semibold text-[var(--text-secondary)]">Categoría</span>
                                <span class="mt-1 block font-medium text-[var(--text-primary)]">{{ $item->category->name ?? $item->categoria ?? 'Sin categoría' }}</span>
                            </div>
                        @endif
                        @if(!empty($item->stock))
                            <div class="rounded-xl bg-black/5 p-3 dark:bg-white/5">
                                <span class="block font-semibold text-[var(--text-secondary)]">Stock</span>
                                <span class="mt-1 block font-medium text-[var(--text-primary)]">{{ $item->stock }} disponibles</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="flex items-center justify-end gap-3 border-t border-black/10 px-6 py-4 dark:border-white/10">
            {{-- <button type="button" @click="showProductModal = false" class="rounded-xl border border-black/10 px-4 py-2 text-sm font-semibold transition hover:bg-black/5 dark:border-white/10 dark:hover:bg-white/10">Cerrar</button> --}}
            <button type="button" x-data="{ anim:false }" @click="anim = true; window.cartAdd({{ $item->id }}); setTimeout(() => anim = false, 350)" wire:click="addToCart({{ $item->id }})" :class="anim ? 'scale-105 shadow-2xl ring-4 ring-white/20' : ''" class="rounded-xl bg-[var(--primary-btn)] px-5 py-2 text-sm font-semibold text-white transition transform duration-200 ease-out hover:scale-105 active:scale-95">Agregar al carrito</button>
        </footer>
    </div>
</div>
</div>
