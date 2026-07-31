{{-- Definimos los colores desde la BD, con valores por defecto si son nulos --}}
@php
    $pColor = $catalogo->theme->primary_color ?? '#4F46E5';
    $bgColor = $catalogo->theme->bg_color ?? '#F2F2F2';
    $sColor = $catalogo->theme->secondary_color ?? '#f0f0f0';
    $pFont = $catalogo->theme->primary_font_color ?? '#333333';
    $sFont = $catalogo->theme->secondary_font_color ?? '#666666';
    
    // Helper to determine if primary color is "dark"
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

<div class="min-h-screen pb-10" 
     style="--primary-btn: {{ $pColor }}; 
            --bg-main: {{ $bgColor }}; 
            --bg-card-aside: {{ $sColor }}; 
            --text-primary: {{ $pFont }}; 
            --text-secondary: {{ $sFont }}; 
            background-color: var(--bg-main);">
    
    <x-alert alert_type="success" />
    
    <x-redes :catalogo="$catalogo" />

    {{-- Configuración Button --}}
    <x-setting-c :catalogo="$catalogo"/>
    <div class="absolute right-35 top-4 z-50">
        <a href="{{ route('catalogo.cart', $catalogo->name) }}" class="fixed  rounded-full border border-white bg-white px-4 py-2 text-sm font-semibold text-black shadow-lg hover:bg-gray-100" style="border-color: var(--primary-btn);">
            {{ __('messages.cart') }}
            <span class="inline-flex h-6 min-w-[1.5rem] items-center justify-center rounded-full bg-indigo-600 text-white text-xs">{{ $cartItemCount }}</span>
        </a>
    </div>
    {{-- Banner & Logo --}}
    <div class="relative">
        @if ($catalogo->banner_url && $catalogo->logo_url)
            <div class="w-full h-48 sm:h-64 lg:h-80 overflow-hidden rounded-b-[2rem] lg:rounded-b-[3rem] shadow-lg">
                <img src="{{ asset('storage/' . $catalogo->banner_url) }}" alt="Banner" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-105">
            </div>
            <div class="absolute left-1/2 -bottom-12 sm:-bottom-16 lg:-bottom-20 transform -translate-x-1/2">
                <div class="relative">
                    <img src="{{ asset('storage/' . $catalogo->logo_url) }}" alt="Logo" class="w-24 h-24 sm:w-32 sm:h-32 lg:w-40 lg:h-40 rounded-full border-4 border-white shadow-xl object-cover" style="border-color: var(--primary-btn); background: white;">
                </div>
            </div>
        @else
            <div class="w-full h-48 sm:h-64 flex items-center justify-center rounded-b-[2rem] shadow-inner italic" style="background: linear-gradient(to right, rgba(200,200,200,0.2), var(--primary-btn)); opacity: 0.5; color: var(--text-secondary);">
                {{ __('messages.no_banner_logo') }}
            </div>
        @endif
    </div>

    {{-- Catalog Title --}}
    <div class="mt-16 sm:mt-20 lg:mt-24 mb-8 text-center px-6 sm:px-12 lg:px-20">
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold mb-2 tracking-tight" style="color: var(--text-primary);">{{ $catalogo->name }}</h1>
        <p class="text-base sm:text-lg max-w-2xl mx-auto leading-relaxed line-clamp-3" style="color: var(--text-primary);">{{ $catalogo->description }}</p>
    </div>

    <x-store-info :catalogo="$catalogo" :icon-color="$iconColor" />

    {{-- Search --}}
    <div class="max-w-7xl mx-auto px-4 mb-8 md:w-1/2">
       <input type="text" wire:model.live="search" 
                           class="w-full p-4 pl-12 rounded-2xl border-none shadow-sm focus:ring-2 outline-none" 
                           style="background-color: var(--bg-card-aside); color: var(--text-secondary); --tw-ring-color: var(--primary-btn);" 
                           placeholder="{{__('messages.search_products')}}..." />
    </div>

    {{-- Categorías --}}
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center gap-3 overflow-x-auto py-4 no-scrollbar">
            <button
                type="button"
                wire:click="filterByCategory(null)"
                class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-full border transition-colors duration-150"
                style="background-color: {{  $selectedCategory === null ? 'var(--primary-btn)' : 'var(--bg-card-aside)' }}; 
                       color: {{ $selectedCategory === null ? $iconColor : 'var(--text-primary)' }}; 
                       border-color: {{ $selectedCategory === null ? 'var(--bg-card-aside)' : 'var(--primary-btn)' }};">
                {{ __('messages.all') }}
            </button>

               @foreach ($catalogo->categories as $category)
                <button
                    type="button"
                    wire:click="filterByCategory({{ $category->id }})"
                    class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-full border transition-colors duration-150"
                    style="background-color: {{  $selectedCategory === null ? 'var(--primary-btn)' : 'var(--bg-card-aside)' }}; 
                       color: {{ $selectedCategory === null ? $iconColor : 'var(--text-primary)' }}; 
                       border-color: {{ $selectedCategory === null ? 'var(--bg-card-aside)' : 'var(--primary-btn)' }};">
                    <span class="truncate max-w-[10rem] text-sm">{{ $category->name }}</span>
                </button>
            @endforeach
        </div>
    </div>

{{-- Productos --}}
<div class="max-w-7xl mx-auto px-4">
    <h2 class="text-2xl font-bold mb-6" style="color: var(--text-primary);">{{ __('messages.products') }}</h2>
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-6">
        @forelse ($catalogo->products as $item) {{-- CAMBIO: Usa $products, no $catalogo->products --}}
            <x-product-card 
                :catalogo="$catalogo" 
                :item="$item" 
                :iconColor="$iconColor" 
                wire:key="prod-{{ $item->id }}" {{-- CAMBIO: Key única obligatoria --}}
            />
        @empty
            <div class="col-span-full py-20 text-center opacity-40 font-bold" style="color: var(--text-secondary);">
                {{ __('messages.no_products') }}
            </div>
        @endforelse
    </div>
    
    {{-- Agrega la paginación aquí abajo --}}
    <div class="mt-8">
        {{ $catalogo->products->links() }}
    </div>
</div>

</div>
    