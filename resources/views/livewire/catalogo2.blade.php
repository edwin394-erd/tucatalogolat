@php
    $pColor = $catalogo->theme->primary_color ?? '#000000';
    $bgColor = $catalogo->theme->bg_color ?? '#ffffff';
    $sColor = $catalogo->theme->secondary_color ?? '#f0f0f0';
    $pFont  = $catalogo->theme->primary_font_color ?? '#333333';
    $sFont  = $catalogo->theme->secondary_font_color ?? '#fff';
@endphp

  @php
    // Helper to determine if primary color is "dark"
    function isDarkColor($hex) {
        $hex = str_replace('#', '', $hex);
        if(strlen($hex) == 3) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
        // Perceived brightness formula
        return (($r * 299 + $g * 587 + $b * 114) / 1000) < 128;
    }
    $iconColor = isDarkColor($pColor) ? '#ffffff' : '#000000';
@endphp


<div class="flex flex-col lg:flex-row min-h-screen" 
     style="--primary-btn: {{ $pColor }}; 
            --bg-main: {{ $bgColor }}; 
            --bg-card-aside: {{ $sColor }}; 
            --text-primary: {{ $pFont }}; 
            --text-secondary: {{ $sFont }}; 
            background-color: var(--bg-main);">

    <x-alert alert_type="success" />

    <x-redes :catalogo="$catalogo" />



    {{-- Sidebar --}}
    <aside class="w-full lg:w-64 shadow-xl flex flex-col py-8 px-6 lg:fixed lg:h-full z-40 transition-colors overflow-hidden" 
           style="background-color: var(--bg-card-aside); color: var(--text-primary);">
        
        {{-- Perfil / Logo --}}
        <div class="flex flex-col items-center mb-6 flex-shrink-0">
            @if ($catalogo->logo_url)
                <img src="{{ asset('storage/' . $catalogo->logo_url) }}" alt="Logo" 
                     class="w-20 h-20 rounded-full border-4 shadow-md object-cover mb-3" 
                     style="border-color: var(--primary-btn);">
            @else
                <div class="w-20 h-20 rounded-full border-4 flex items-center justify-center text-[10px] opacity-50 mb-3" 
                     style="border-color: var(--primary-btn); background-color: var(--bg-main);">{{__('messages.no_logo')}}</div>
            @endif
            <h2 class="text-lg font-black text-center leading-tight line-clamp-1" style="color: var(--text-primary);">{{ $catalogo->name }}</h2>
            {{-- Solución Problema 1: Descripción muy larga --}}
            {{-- <p class="text-xs text-center mt-2 opacity-80 line-clamp-3 px-2" style="color: var(--text-secondary);">{{ $catalogo->description }}</p> --}}
        </div>
       
        <x-store-info :catalogo="$catalogo" :icon-color="$iconColor" />

        {{-- Solución Problema 2: Muchas categorías (Select en móvil, Lista en desktop) --}}
<nav class="flex-1 lg:overflow-y-auto pr-2 custom-scrollbar">
    <li class="mb-3 opacity-50 text-[10px] font-bold uppercase tracking-widest list-none" style="color: var(--text-primary);">{{__('messages.categories')}}</li>
    
    <div class="lg:hidden mb-6">
        <select wire:change="filterByCategory($event.target.value)" 
                class="w-full p-3 rounded-xl border-none shadow-sm focus:ring-2 outline-none text-sm"
                style="background-color: var(--bg-main); color: var(--text-primary); --tw-ring-color: var(--primary-btn);">
            <option value="">{{__('messages.all')}}</option>
            @foreach ($catalogo->categories as $categoria)
                <option value="{{ $categoria->id }}">{{ $categoria->name }}</option>
            @endforeach
        </select>
    </div>

    <ul class="hidden lg:block space-y-1">
        <li>
            <a href="javascript:void(0)" wire:click="filterByCategory(null)" 
               class="flex items-center py-2 px-4 rounded-lg text-sm transition-all hover:translate-x-1 {{ is_null($selectedCategory) ? 'font-bold' : '' }}"
               style="color: var(--text-primary); {{ is_null($selectedCategory) ? 'border-left: 4px solid var(--primary-btn); background-color: rgba(0,0,0,0.05);' : '' }}">
               {{__('messages.all')}}
            </a>
        </li>
        @foreach ($catalogo->categories as $categoria)
            <li>
                <a href="javascript:void(0)" wire:click="filterByCategory({{ $categoria->id }})" 
                   class="flex items-center py-2 px-4 rounded-lg text-sm transition-all hover:translate-x-1 hover:bg-black/5 {{ $selectedCategory == $categoria->id ? 'font-bold' : '' }}"
                   style="color: var(--text-primary); {{ $selectedCategory == $categoria->id ? 'border-left: 4px solid var(--primary-btn); background-color: rgba(0,0,0,0.05);' : '' }}">
                    {{ $categoria->name }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>

        {{-- Botón de Ajustes (Fijo abajo si sobra espacio) --}}
    @if (auth()->check() && auth()->user()->id === $catalogo->user_id)
            <div class="mt-auto pt-4 flex-shrink-0">
                <a wire:navigate href="{{ route('configuracion') }}" 
                   class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-white bg-indigo-600 text-sm font-bold shadow-lg transition-transform active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                    </svg>
                    {{__('messages.settings')}}
                </a>
            </div>
        @endif
    </aside>

    {{-- Main Content --}}
    <main class="mt-4 md:mt-0  flex-1 lg:ml-64 p-4 lg:p-8">
        
        <div class="max-w-7xl mx-auto">
            {{-- Search & Header --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
                <div class="space-y-2">
                    <h2 class="text-3xl font-black tracking-tight" style="color: var(--text-primary);">{{__('messages.products')}}</h2>
                </div>
                <div class="flex  gap-3 justify-end w-full">
                    <a href="{{ route('catalogo.cart', 
                    $catalogo->name) }}" class="inline-flex items-center gap-2 rounded-full border border-white bg-white px-4 py-2 text-sm font-semibold text-black shadow hover:bg-gray-100" style="border-color: var(--primary-btn);">
                        {{ __('messages.cart') }}
                        <span class="inline-flex h-6 min-w-[1.5rem] items-center justify-center rounded-full bg-indigo-600 text-white text-xs">{{ $cartItemCount }}</span>
                    </a>
                    <div class="relative w-full md:w-96">
                    <input type="text" wire:model.live="search" 
                           class="w-full p-4 pl-12 rounded-2xl border-none shadow-sm focus:ring-2 outline-none" 
                           style="background-color: var(--bg-card-aside); color: var(--text-primary); --tw-ring-color: var(--primary-btn);" 
                           placeholder="{{__('messages.search_products')}}..." />
                    <svg class="absolute left-4 top-4 w-5 h-5 opacity-40" style="color: var(--text-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <div class="mb-8">
                {{ $catalogo->products->links() }}
            </div>
        </div>
            
            {{-- Grid --}}
          {{-- Grid Optimizado --}}
<div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-6 flex-wrap">
    @forelse ($catalogo->products as $item)
        <x-product-card  :catalogo="$catalogo" :item="$item" :iconColor="$iconColor" />
     
        
    @empty
        <div class="col-span-full py-20 text-center opacity-40 font-bold" style="color: var(--text-primary);">{{__('messages.no_products')}}</div>
    @endforelse
</div>
</div>
        
    </main>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
    </style>
</div>
