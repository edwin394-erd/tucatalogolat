@section('title', $catalogo->name ?? 'Catalogo')
@section('description', \Illuminate\Support\Str::limit(strip_tags($catalogo->description ?? 'Descubre productos y servicios en este catálogo.'), 160))
@section('og_title', $catalogo->name ?? 'Catalogo')
@section('og_image', $catalogo->logo_url ? asset('storage/' . $catalogo->logo_url) : asset('imgs/icono.ico'))
@section('canonical', route('catalogo', $catalogo->name_handle))

@php
    $pColor = $catalogo->theme->primary_color ?? '#4F46E5';
    $bgColor = $catalogo->theme->bg_color ?? '#F2F2F2';
    $sColor = $catalogo->theme->secondary_color ?? '#f0f0f0';
    $pFont = $catalogo->theme->primary_font_color ?? '#333333';
    $sFont = $catalogo->theme->secondary_font_color ?? '#666666';

    function isDarkColor($hex) {
        $hex = str_replace('#', '', $hex);
        if(strlen($hex) == 3) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
        return (($r * 299 + $g * 587 + $b * 114) / 1000) < 128;
    }
    $iconColor = isDarkColor($pColor) ? '#ffffff' : '#000000';

    function contrastRatio($hex1, $hex2) {
    $lum = function($hex) {
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        $r = hexdec(substr($hex,0,2)) / 255;
        $g = hexdec(substr($hex,2,2)) / 255;
        $b = hexdec(substr($hex,4,2)) / 255;
        $chan = function($c) {
            return $c <= 0.03928 ? $c / 12.92 : pow(($c + 0.055) / 1.055, 2.4);
        };
        return 0.2126 * $chan($r) + 0.7152 * $chan($g) + 0.0722 * $chan($b);
    };
    $l1 = $lum($hex1); $l2 = $lum($hex2);
    $lighter = max($l1, $l2); $darker = min($l1, $l2);
    return round(($lighter + 0.05) / ($darker + 0.05), 2);
}

    function contrastBadge($ratio) {
        if ($ratio >= 4.5) return ['label' => '✓ Buen contraste', 'class' => 'bg-green-100 text-green-700'];
        if ($ratio >= 3)   return ['label' => '⚠ Contraste bajo', 'class' => 'bg-yellow-100 text-yellow-700'];
        return ['label' => '✗ Texto ilegible', 'class' => 'bg-red-100 text-red-700'];
    }
@endphp

<div>
<div class="min-h-screen pb-16"
     style="--primary-btn: {{ $pColor }};
            --bg-main: {{ $bgColor }};
            --bg-card-aside: {{ $sColor }};
            --text-primary: {{ $pFont }};
            --text-secondary: {{ $sFont }};
            background-color: var(--bg-main);">

    <x-alert alert_type="success" />
    <x-redes :catalogo="$catalogo" />
    <x-setting-c :catalogo="$catalogo"/>

    {{-- Un solo contenedor para todo el contenido: mismo ancho y padding en cada sección --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Banner & Logo --}}
{{-- Header compacto: banner + logo + título en un solo bloque, sin salto de espacio --}}
<div class="relative rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg mb-8">
    <div class="w-full aspect-[16/7] sm:aspect-[3/1] max-h-72">
        @if ($catalogo->banner_url)
            <img src="{{ asset('storage/' . $catalogo->banner_url) }}" alt="Banner" class="w-full h-full object-cover object-center">
        @else
            <div class="w-full h-full" style="background: linear-gradient(135deg, var(--primary-btn), var(--bg-card-aside));"></div>
        @endif

        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/40 via-40% to-transparent"></div>
    </div>


   {{-- Iconos en la esquina superior derecha, SOLO en móvil --}}
    <div class="absolute top-3 right-3 sm:hidden">
        <x-store-info :catalogo="$catalogo" :icon-color="$iconColor" />
    </div>

    {{-- Logo + nombre, superpuestos abajo del banner --}}
    <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-6 flex items-center gap-3 sm:gap-4">
        @if ($catalogo->logo_url)
            <img src="{{ asset('storage/' . $catalogo->logo_url) }}" alt="Logo" class="w-14 h-14 sm:w-20 sm:h-20 rounded-full border-2 border-white shadow-xl object-cover flex-shrink-0">
        @else
            <div class="w-14 h-14 sm:w-20 sm:h-20 rounded-full border-2 border-white shadow-xl flex items-center justify-center font-bold text-white flex-shrink-0"
                 style="background-color: var(--primary-btn);">
                {{ strtoupper(substr($catalogo->name ?? 'C', 0, 2)) }}
            </div>
        @endif

        <div class="min-w-0">
            <h1 class="text-lg sm:text-2xl lg:text-3xl font-extrabold text-white truncate [text-shadow:_0_1px_4px_rgb(0_0_0_/_70%)]">{{ $catalogo->name }}</h1>
        </div>

        {{-- Iconos junto al nombre, SOLO en pantallas sm+ (se ocultan en móvil, ya están arriba) --}}
        <div class="ml-auto flex-shrink-0 hidden sm:block">
            <x-store-info :catalogo="$catalogo" :icon-color="$iconColor" />
        </div>
    </div>
</div>

        {{-- Toolbar: buscador + categorías en una sola fila en desktop, apilados en móvil --}}
        <div class="sticky top-0 z-20 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6 backdrop-blur-sm"
             style="background-color: color-mix(in srgb, var(--bg-main) 92%, transparent);">
            <div class="flex flex-col lg:flex-row lg:items-center gap-3">
                <div class="w-full lg:w-80 flex-shrink-0">
                          <input type="text" wire:model.live.debounce.300ms="search"
                              class="h-10 w-full px-4 py-2 rounded-full border-none shadow-sm focus:ring-2 outline-none"
                           style="background-color: var(--bg-card-aside); color: var(--text-secondary); --tw-ring-color: var(--primary-btn);"
                           placeholder="{{ __('messages.search_products') }}...">
                </div>

                <div class="flex items-center gap-3 overflow-x-auto no-scrollbar lg:flex-1">
                    <button type="button" wire:click="filterByCategory(null)"
                            class="h-10 flex-shrink-0 inline-flex items-center gap-2 px-4 py-0 rounded-full border text-sm transition-colors duration-150"
                            style="background-color: {{ $selectedCategory === null ? 'var(--primary-btn)' : 'var(--bg-card-aside)' }};
                                color: {{ $selectedCategory === null ? $iconColor : 'var(--text-secondary)' }};
                                border-color: {{ $selectedCategory === null ? 'var(--bg-card-aside)' : 'var(--primary-btn)' }};">
                        {{ __('messages.all') }}
                    </button>

                @foreach ($catalogo->categories as $category)
                    <button type="button" wire:click="filterByCategory({{ $category->id }})"
                            class="h-10 flex-shrink-0 inline-flex items-center gap-2 px-4 py-0 rounded-full border text-sm transition-colors duration-150"
                            style="background-color: {{ $selectedCategory == $category->id ? 'var(--primary-btn)' : 'var(--bg-card-aside)' }};
                                color: {{ $selectedCategory == $category->id ? $iconColor : 'var(--text-secondary)' }};
                                border-color: {{ $selectedCategory == $category->id ? 'var(--bg-card-aside)' : 'var(--primary-btn)' }};">
                        <span class="truncate max-w-[10rem]">{{ $category->name }}</span>
                    </button>
                @endforeach
                </div>
            </div>

            @if ($search)
                <p class="text-sm mt-3" style="color: var(--text-secondary);">
                    {{ __('messages.search_results_for') }} <span class="font-semibold">"{{ $search }}"</span>
                </p>
            @endif
        </div>

        {{-- Productos --}}
        <h2 class="text-2xl font-bold mb-6" style="color: var(--text-primary);">{{ __('messages.products') }}</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 2xl:grid-cols-5 gap-4 md:gap-6">
            @forelse ($catalogo->products as $item)
                <x-product-card
                    :catalogo="$catalogo"
                    :item="$item"
                    :iconColor="$iconColor"
                    wire:key="prod-{{ $item->id }}"
                />
            @empty
                <div class="col-span-full py-20 text-center opacity-40 font-bold" style="color: var(--text-secondary);">
                    {{ __('messages.no_products') }}
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $catalogo->products->links() }}
        </div>
    </div>
    <footer class="flex items-center justify-center gap-2 px-4 py-2 text-center text-xs" style="background-color: {{ $catalogo->theme->bg_color ?? '#ffffff' }}; color: {{ isDarkColor($bgColor) ? '#ffffff' : '#000000' }};">
        <span class="leading-none">Powered by</span>
        <a href="{{ url()->to('https://tucatalogolat.lat') }}" class="inline-flex items-center gap-1 font-bold leading-none hover:underline">
            <span>tucatalogolat.lat</span>
            <img src="{{ asset('imgs/icono.ico') }}" alt="TuCatalogo.Lat" class="h-4 w-4 object-contain">
        </a>
    </footer>

        
</div>

<style>
.no-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: var(--primary-btn) transparent;
}
.no-scrollbar::-webkit-scrollbar {
    height: 4px;
}
.no-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.no-scrollbar::-webkit-scrollbar-thumb {
    background-color: var(--primary-btn);
    border-radius: 10px;
}
</style>

</div>

