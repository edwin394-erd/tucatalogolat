<div x-data="{ showProductModal: false }" @click="showProductModal = true" @keydown.enter.prevent="showProductModal = true" @keydown.space.prevent="showProductModal = true" tabindex="0" role="button" class="relative rounded-2xl sm:rounded-3xl overflow-hidden flex flex-col h-full group border border-black/5 bg-[var(--bg-card-aside)] shadow-[0_2px_10px_rgba(0,0,0,0.06)] hover:shadow-[0_12px_32px_rgba(0,0,0,0.14)] transition-shadow duration-500 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-black/10">

    {{-- Imagen --}}
    <div class="relative w-full aspect-square overflow-hidden shrink-0">
        @if(!empty($item->fotos) && isset($item->fotos[0]))
            <img src="{{ asset('storage/' . $item->fotos[0]->url) }}"
                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110"
                 loading="lazy" alt="{{ $item->name }}">
        @else
            <div class="absolute inset-0 flex items-center justify-center text-xs opacity-50" style="color: var(--text-secondary); background-color: var(--bg-main);">Sin imagen</div>
        @endif

        <div class="absolute inset-x-0 bottom-0 h-12 sm:h-16 bg-gradient-to-t from-black/10 to-transparent pointer-events-none"></div>

        @if($item->precio_descuento)
            <div class="absolute top-2 left-2 sm:top-3 sm:left-3 px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full text-[9px] sm:text-[10px] font-bold backdrop-blur-md shadow-sm"
                 style="background-color: color-mix(in srgb, var(--primary-btn) 85%, transparent); color: {{ $iconColor }};">
                -{{ round((1 - $item->precio_descuento / $item->price) * 100) }}%
            </div>
        @endif

    </div>

    {{-- Contenido: todo el espaciado vertical se controla desde un solo lugar --}}
    <div class="flex flex-col flex-1 min-w-0 p-2.5 sm:p-4 gap-1.5 sm:gap-2">

        <div class="min-w-0">
            <h5 class="text-xs sm:text-sm md:text-base font-bold truncate leading-tight tracking-tight" style="color: var(--text-secondary);">
                {{ $item->name }}
            </h5>
            <p class="hidden sm:block text-xs md:text-sm mt-0.5 leading-snug break-words overflow-hidden" style="display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2; color: var(--text-secondary); opacity: 0.65;">
                {{ $item->description }}
            </p>
        </div>

        {{-- Precio --}}
        <div class="flex items-baseline gap-1.5 sm:gap-2 min-w-0">
            @if($item->precio_descuento)
                <span class="text-sm sm:text-lg md:text-xl font-black truncate" style="color: var(--text-secondary);">
                    ${{ number_format($item->precio_descuento, 2) }}
                </span>
                <span class="text-[10px] sm:text-xs font-medium opacity-40 line-through truncate" style="color: var(--text-secondary);">
                    ${{ number_format($item->price, 2) }}
                </span>
            @else
                <span class="text-sm sm:text-lg md:text-xl font-black truncate" style="color: var(--text-secondary);">
                    ${{ number_format($item->price, 2) }}
                </span>
            @endif
        </div>

        {{-- Acción principal --}}
        <div x-data class="mt-auto pt-0.5">
            <template x-if="(Alpine.store('cart') && (Alpine.store('cart').items['{{ $item->id }}'] || 0)) == 0">
                <button type="button" x-on:click.stop="window.cartAdd({{ $item->id }})" title="Agregar al carrito"
                        class="w-full h-8 sm:h-10 rounded-xl sm:rounded-2xl inline-flex items-center justify-center gap-1.5 sm:gap-2 text-xs sm:text-sm font-semibold shadow-md transition-all duration-200 hover:shadow-lg hover:brightness-105 active:scale-[0.98]"
                        style="background-color: var(--primary-btn); color: {{ $iconColor }};">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 sm:w-4.5 sm:h-4.5">
                        <path d="M6 6h15l-1.68 9.39a2 2 0 0 1-1.99 1.61H8.31a2 2 0 0 1-1.99-1.61L4.57 4H2" />
                        <circle cx="9" cy="20" r="1" />
                        <circle cx="16" cy="20" r="1" />
                    </svg>
                    <span class="hidden xs:inline sm:inline">Agregar</span>
                </button>
            </template>
            <template x-if="(Alpine.store('cart') && (Alpine.store('cart').items['{{ $item->id }}'] || 0)) > 0">
                <div class="flex items-center justify-between w-full h-8 sm:h-10 rounded-xl sm:rounded-2xl border border-black/10 bg-white/50 backdrop-blur-sm px-1 sm:px-1.5 shadow-inner">
                    <button type="button" @click.stop="window.cartDecrease({{ $item->id }})" class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-white/70 text-sm sm:text-base font-semibold transition hover:bg-white active:scale-95 text-[var(--text-secondary)]">−</button>
                    <div class="flex-1 text-center text-xs sm:text-sm font-bold text-[var(--text-secondary)]" x-text="Alpine.store('cart') ? (Alpine.store('cart').items['{{ $item->id }}'] || 0) : 0"></div>
                    <button type="button" @click.stop="window.cartIncrease({{ $item->id }})" class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl text-sm sm:text-base font-semibold transition hover:brightness-105 active:scale-95" style="background-color: var(--primary-btn); color: {{ $iconColor }};">+</button>
                </div>
            </template>
        </div>
    </div>

    {{-- Modal: bottom-sheet en móvil, dialog centrado en desktop --}}
    <div x-show="showProductModal" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/50 backdrop-blur-sm">
        <div x-show="showProductModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             @click.away="showProductModal = false" @keydown.escape.window="showProductModal = false"
             class="flex max-h-[92vh] sm:max-h-[85vh] w-full sm:max-w-xl flex-col overflow-hidden rounded-t-3xl sm:rounded-[2rem] bg-[var(--bg-card-aside)] text-[var(--text-secondary)] shadow-2xl ring-1 ring-black/5">

            {{-- Handle visual para indicar "arrastrable" en móvil --}}
            <div class="sm:hidden flex justify-center pt-2.5 pb-1 shrink-0">
                <div class="w-10 h-1 rounded-full bg-black/15"></div>
            </div>

            <header class="flex items-center justify-between border-b border-black/5 px-5 sm:px-6 py-3 sm:py-4 shrink-0">
                <div class="min-w-0">
                    <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[var(--text-secondary)] opacity-50">Producto</span>
                    <h3 class="text-lg sm:text-xl font-bold text-[var(--text-secondary)] truncate">{{ $item->name }}</h3>
                </div>
                <button type="button" @click.stop="showProductModal = false" class="shrink-0 flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-full text-xl font-bold transition hover:bg-black/5 active:scale-90" aria-label="Cerrar">&times;</button>
            </header>

            <main class="grid grid-cols-1 flex-1 gap-5 sm:gap-6 overflow-y-auto p-5 sm:p-6 sm:grid-cols-[160px_1fr] md:grid-cols-[180px_1fr]">
                <div class="relative mx-auto w-full max-w-[130px] sm:max-w-none h-[130px] sm:h-auto overflow-hidden rounded-2xl bg-[var(--bg-main)] shadow-inner sm:mx-0 sm:aspect-square">
                    @if(!empty($item->fotos) && isset($item->fotos[0]))
                        <img src="{{ asset('storage/' . $item->fotos[0]->url) }}" alt="{{ $item->name }}" class="w-full h-full object-cover" loading="lazy">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-xs opacity-50">Sin imagen</div>
                    @endif
                </div>

                <div class="flex flex-col gap-4 min-w-0">
                    <div>
                        <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[var(--text-secondary)] opacity-50">Precio</span>
                        <p class="text-xl sm:text-2xl font-black text-[var(--text-secondary)]">
                            @if($item->precio_descuento)
                                ${{ number_format($item->precio_descuento, 2) }}
                                <span class="ml-2 text-sm font-normal line-through text-[var(--text-secondary)] opacity-50">${{ number_format($item->price, 2) }}</span>
                            @else
                                ${{ number_format($item->price, 2) }}
                            @endif
                        </p>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-[var(--text-secondary)] opacity-80">Descripción</span>
                        <p class="mt-0.5 max-h-32 overflow-y-auto text-sm leading-relaxed break-words text-[var(--text-secondary)] opacity-70" style="display: block; word-break: break-word;">{{ $item->description }}</p>
                    </div>

                    @if(!empty($item->category_id) || !empty($item->categoria) || !empty($item->stock))
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            @if(!empty($item->category_id) || !empty($item->categoria))
                                <div class="rounded-2xl bg-black/[0.03] p-3 min-w-0">
                                    <span class="block font-semibold text-[var(--text-secondary)] opacity-60">Categoría</span>
                                    <span class="mt-1 block font-medium text-[var(--text-secondary)] truncate">{{ $item->category->name ?? $item->categoria ?? 'Sin categoría' }}</span>
                                </div>
                            @endif
                            @if(!empty($item->stock))
                                <div class="rounded-2xl bg-black/[0.03] p-3">
                                    <span class="block font-semibold text-[var(--text-secondary)] opacity-60">Stock</span>
                                    <span class="mt-1 block font-medium text-[var(--text-secondary)]">{{ $item->stock }} disponibles</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </main>

            <footer class="flex items-center justify-end gap-3 border-t border-black/5 px-5 sm:px-6 py-3 sm:py-4 shrink-0" style="padding-bottom: max(0.75rem, env(safe-area-inset-bottom));">
                <template x-if="(Alpine.store('cart') && (Alpine.store('cart').items['{{ $item->id }}'] || 0)) == 0">
                    <button type="button" x-data="{ anim:false }" @click.stop="anim = true; window.cartAdd({{ $item->id }}); setTimeout(() => anim = false, 350)" wire:click="addToCart({{ $item->id }})"
                            :class="anim ? 'scale-105 shadow-2xl ring-4 ring-black/5' : ''"
                            class="w-full sm:w-auto rounded-2xl px-6 py-2.5 text-sm font-semibold transition transform duration-200 ease-out hover:scale-105 active:scale-95 shadow-md"
                            style="background-color: var(--primary-btn); color: {{ $iconColor }};">
                        Agregar al carrito
                    </button>
                </template>

                <template x-if="(Alpine.store('cart') && (Alpine.store('cart').items['{{ $item->id }}'] || 0)) > 0">
                    <div class="flex items-center justify-between w-full sm:w-auto min-w-[140px] h-11 rounded-2xl border border-black/10 bg-white/50 backdrop-blur-sm px-1.5 shadow-inner">
                        <button type="button" @click.stop="window.cartDecrease({{ $item->id }})" class="w-8 h-8 rounded-xl bg-white/70 text-lg font-semibold transition hover:bg-white active:scale-95 text-[var(--text-secondary)]">−</button>
                        <div class="flex-1 text-center text-sm font-bold text-[var(--text-secondary)]" x-text="Alpine.store('cart') ? (Alpine.store('cart').items['{{ $item->id }}'] || 0) : 0"></div>
                        <button type="button" @click.stop="window.cartIncrease({{ $item->id }})" class="w-8 h-8 rounded-xl text-lg font-semibold transition hover:brightness-105 active:scale-95" style="background-color: var(--primary-btn); color: {{ $iconColor }};">+</button>
                    </div>
                </template>
            </footer>
        </div>
    </div>
</div>