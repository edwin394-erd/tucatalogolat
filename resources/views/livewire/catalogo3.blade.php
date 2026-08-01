@php
    $pColor = $catalogo->theme->primary_color ?? '#000000';
    $bgColor = $catalogo->theme->bg_color ?? '#ffffff';
    $sColor = $catalogo->theme->secondary_color ?? '#f0f0f0';
    $pFont  = $catalogo->theme->primary_font_color ?? '#333333';
    $sFont  = $catalogo->theme->secondary_font_color ?? '#666666';
@endphp

@php
    function isDarkColor($hex) {
        $hex = str_replace('#', '', $hex);
        if(strlen($hex) == 3) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
        return (($r * 299 + $g * 587 + $b * 114) / 1000) < 128;
    }
    $iconColor = isDarkColor($pColor) ? '#ffffff' : '#000000';
@endphp


<div class="min-h-screen" style="background-color: {{ $bgColor }}; color: {{ $pFont }};">
    <x-redes :catalogo="$catalogo" />
    <x-alert alert_type="success" />

    <x-redes :catalogo="$catalogo" />
       {{-- Configuración Button --}}
    <x-setting-c :catalogo="$catalogo"/>

    <header class="relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(255,255,255,0.25),_transparent_35%)]"></div>
        <div class="max-w-7xl mx-auto px-4 py-16 lg:py-24 relative">
            <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr] items-center">
                <div class="space-y-6">
                    <span class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold" style="background-color: {{ $sColor }}; color: {{ $pFont }};">Menú de restaurante</span>
                    <h1 class="text-4xl sm:text-5xl font-extrabold" style="color: {{ $pColor }};">{{ $catalogo->name }}</h1>
                    <p class="max-w-3xl text-lg leading-8" style="color: {{ $sFont }};">{{ $catalogo->description }}</p>
                    <div class="flex flex-wrap gap-3 mt-4">
                        @foreach($catalogo->categories as $cat)
                            <button wire:click="filterByCategory({{ $cat->id }})" class="rounded-full px-4 py-2 text-sm font-semibold border" style="border-color: {{ $pColor }}; color: {{ $pColor }}; background-color: rgba(0,0,0,0.03);">{{ $cat->name }}</button>
                        @endforeach
                    </div>
                </div>
                <div class="rounded-3xl border shadow-xl overflow-hidden" style="border-color: {{ $pColor }}; background-color: {{ $sColor }};">
                    @if($catalogo->banner_url)
                        <img src="{{ asset('storage/' . $catalogo->banner_url) }}" alt="{{ $catalogo->name }}" class="h-80 w-full object-cover">
                    @else
                        <div class="h-80 flex items-center justify-center text-center p-8" style="color: {{ $pFont }}; background-color: rgba(0,0,0,0.03);">
                            Sin banner disponible
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <section class="space-y-8">
                @foreach($catalogo->categories as $category)
                    @php
                        $categoryProducts = $catalogo->products->where('category_id', $category->id);
                    @endphp
                    @if($categoryProducts->isNotEmpty())
                        <div class="rounded-3xl border border-gray-200 bg-white shadow-sm p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h2 class="text-2xl font-bold" style="color: {{ $pColor }};">{{ $category->name }}</h2>
                                    <p class="text-sm" style="color: {{ $sFont }};">{{ $category->description ?? 'Sección de menú' }}</p>
                                </div>
                                <span class="rounded-full px-3 py-1 text-sm font-semibold" style="background-color: {{ $pColor }}; color: {{ $iconColor }};">{{ $categoryProducts->count() }} items</span>
                            </div>
                            <div class="space-y-4">
                                @foreach($categoryProducts as $item)
                                    <div class="flex flex-col gap-4 rounded-3xl border p-4 md:flex-row md:items-center md:justify-between" style="background-color: {{ $sColor }};">
                                        <div class="flex-1 md:flex md:items-center md:gap-4">
                                            @if($item->fotos->isNotEmpty())
                                                <div class="h-32 w-full overflow-hidden rounded-3xl bg-gray-100 md:h-28 md:w-28">
                                                    <img src="{{ asset('storage/' . $item->fotos->first()->url) }}" alt="{{ $item->name }}" class="h-full w-full object-cover">
                                                </div>
                                            @endif
                                            <div class="mt-4 md:mt-0">
                                                <div class="flex items-center gap-3">
                                                    <h3 class="text-xl font-semibold" style="color: {{ $pColor }};">{{ $item->name }}</h3>
                                                    <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700">${{ number_format($item->price, 2) }}</span>
                                                </div>
                                                <p class="mt-2 text-sm leading-relaxed" style="color: {{ $sFont }};">{{ $item->description }}</p>
                                            </div>
                                        </div>
                                        <div class="flex flex-col gap-2 items-start md:items-end">
                                            <button type="button" x-data="{ added: false }" @click="added = true; setTimeout(() => added = false, 400); window.dispatchEvent(new CustomEvent('cart-added'))" wire:click="addToCart({{ $item->id }})" :class="added ? 'scale-125 shadow-2xl ring-4 ring-white/80 animate-pulse' : ''" class="rounded-full px-5 py-2 text-sm font-semibold shadow transition-all duration-200 ease-out" style="background-color: {{ $pColor }}; color: {{ $iconColor }};">Añadir al carrito</button>
                                            @if($item->stock !== null)
                                                <span class="text-xs uppercase tracking-wider" style="color: {{ $sFont }};">{{ __('messages.stock') }}: {{ $item->stock }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </section>

            <aside class="space-y-6">
                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="text-xl font-bold mb-3" style="color: {{ $pColor }};">Información de pedido</h3>
                        <p class="text-sm" style="color: {{ $sFont }};">Agrega tus platos favoritos y solicita tu orden por WhatsApp.</p>
                    <div class="mt-6 space-y-3">
                        <div class="rounded-2xl p-4" style="background-color: {{ $sColor }}; color: {{ $pFont }};">
                            <p class="text-sm font-semibold">Contacto</p>
                            <p class="text-sm">{{ $catalogo->telefono_contacto }}</p>
                        </div>
                        <div class="rounded-2xl p-4" style="background-color: {{ $sColor }}; color: {{ $pFont }};">
                            <p class="text-sm font-semibold">Horario</p>
                            <p class="text-sm">{{ $catalogo->horario ?? 'Abierto todo el día' }}</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-bold mb-3" style="color: {{ $pColor }};">Acciones rápidas</h3>
                    <a href="{{ route('catalogo.cart', $catalogo->name) }}" class="block rounded-full px-4 py-3 text-center font-semibold" style="background-color: {{ $pColor }}; color: {{ $iconColor }};">Ver carrito</a>
                </div>
            </aside>
        </div>
    </main>
</div>
