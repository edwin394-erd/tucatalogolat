@section('title', $catalogo->name ?? 'Catalogo')
@section('description', \Illuminate\Support\Str::limit(strip_tags($catalogo->description ?? 'Descubre productos y servicios en este catálogo.'), 160))
@section('og_title', $catalogo->name ?? 'Catalogo')
@section('og_image', $catalogo->logo_url ? asset('storage/' . $catalogo->logo_url) : asset('imgs/icono.ico'))
@section('canonical', route('catalogo', $catalogo->name_handle))

@php
    $pColor = $catalogo->theme->primary_color ?? '#000000';
    $bgColor = $catalogo->theme->bg_color ?? '#ffffff';
    $sColor = $catalogo->theme->secondary_color ?? '#f0f0f0';
    $pFont  = $catalogo->theme->primary_font_color ?? '#333333';
    $sFont  = $catalogo->theme->secondary_font_color ?? '#fff';
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
    <aside class="w-full lg:w-64 shadow-xl flex flex-col py-5 px-4 lg:fixed lg:h-full z-40 transition-colors overflow-hidden"
           style="background-color: var(--bg-card-aside); color: var(--text-secondary);">

        {{-- Perfil / Logo sobre el banner --}}
        <div class="relative -mx-4 -mt-5 mb-3 h-36 w-[calc(100%+2rem)] flex-shrink-0 overflow-hidden rounded-none">
            @if ($catalogo->banner_url)
                <img src="{{ asset('storage/' . $catalogo->banner_url) }}" alt="{{ $catalogo->name }}"
                     class="absolute inset-0 h-full w-full object-cover object-center">
            @else
                <div class="absolute inset-0" style="background: linear-gradient(135deg, var(--primary-btn), var(--bg-main));"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/25 to-transparent"></div>
            <div class="relative flex h-full flex-col items-center justify-end p-3">
                @if ($catalogo->logo_url)
                    <img src="{{ asset('storage/' . $catalogo->logo_url) }}" alt="Logo"
                         class="h-16 w-16 rounded-full border-4 object-cover shadow-md"
                         style="border-color: var(--primary-btn);">
                @else
                    <div class="flex h-16 w-16 items-center justify-center rounded-full border-4 text-[10px] opacity-80"
                         style="border-color: var(--primary-btn); background-color: var(--bg-main);">{{__('messages.no_logo')}}</div>
                @endif
                <h2 class="mt-2 max-w-full truncate text-center text-base font-black leading-tight text-white drop-shadow">{{ $catalogo->name }}</h2>
            </div>
        </div>

        <x-store-info :catalogo="$catalogo" :icon-color="$iconColor" />

        {{-- Categorías --}}
        <nav class="flex-1 lg:overflow-y-auto pr-2 custom-scrollbar mt-1.5">
            <li class="mb-1.5 opacity-50 text-[10px] font-bold uppercase tracking-widest list-none" style="color: var(--text-secondary);">{{__('messages.categories')}}</li>

            <div class="lg:hidden mb-3">
                <select wire:change="filterByCategory($event.target.value)"
                        class="w-full p-2 rounded-xl border-none shadow-sm focus:ring-2 outline-none text-sm"
                        style="background-color: var(--bg-main); color: var(--text-primary); --tw-ring-color: var(--primary-btn);">
                    <option value="">{{__('messages.all')}}</option>
                    @foreach ($catalogo->categories as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->name }}</option>
                    @endforeach
                </select>
            </div>

            <ul class="hidden lg:block space-y-0.5">
                <li>
                          <a href="javascript:void(0)" wire:click="filterByCategory(null)"
                              class="flex items-center py-1.5 px-3 rounded-lg text-sm transition-all hover:translate-x-1 {{ is_null($selectedCategory) ? 'font-bold' : '' }}"
                              style="color: var(--text-secondary); {{ is_null($selectedCategory) ? 'border-left: 4px solid var(--primary-btn); background-color: rgba(0,0,0,0.05);' : '' }}">
                       {{__('messages.all')}}
                    </a>
                </li>
                @foreach ($catalogo->categories as $categoria)
                    <li>
                                <a href="javascript:void(0)" wire:click="filterByCategory({{ $categoria->id }})"
                                    class="flex items-center py-1.5 px-3 rounded-lg text-sm transition-all hover:translate-x-1 hover:bg-black/5 {{ $selectedCategory == $categoria->id ? 'font-bold' : '' }}"
                                    style="color: var(--text-secondary); {{ $selectedCategory == $categoria->id ? 'border-left: 4px solid var(--primary-btn); background-color: rgba(0,0,0,0.05);' : '' }}">
                            {{ $categoria->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        @if (auth()->check() && auth()->user()->id === $catalogo->user_id)
            <div class="mt-auto pt-2.5 flex-shrink-0">
                <a wire:navigate href="{{ route('configuracion') }}"
                   class="flex items-center justify-center gap-2 py-2 px-4 rounded-xl text-white bg-indigo-600 text-sm font-bold shadow-lg transition-transform active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                    </svg>
                    {{__('messages.settings')}}
                </a>
            </div>
        @endif
    </aside>

    {{-- Main Content --}}
    <main class="mt-3 lg:mt-0 flex-1 lg:ml-64 p-3 sm:p-4 lg:p-5">

        <div class="max-w-7xl mx-auto">

            {{-- Search & Header --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-3">
                <div class="space-y-0.5">
                    <h2 class="text-2xl font-black tracking-tight" style="color: var(--text-primary);">{{__('messages.products')}}</h2>
                    @if ($search)
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ __('messages.search_results_for') }} <span class="font-semibold">"{{ $search }}"</span></p>
                    @endif
                </div>

                <div class="relative w-full md:w-96">
                          <input type="text" wire:model.live.debounce.300ms="search"
                              class="h-10 w-full p-2 pl-10 rounded-full border-none shadow-sm focus:ring-2 outline-none"
                           style="background-color: var(--bg-card-aside); color: var(--text-secondary); --tw-ring-color: var(--primary-btn);"
                           placeholder="{{__('messages.search_products')}}..." />
                    <svg class="absolute left-3 top-3 w-4.5 h-4.5 opacity-40" style="color: var(--text-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            {{-- Grid --}}
            <div class="grid grid-cols-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-5 gap-2 md:gap-3">
                @forelse ($catalogo->products as $item)
                    <x-product-card :catalogo="$catalogo" :item="$item" :iconColor="$iconColor" />
                @empty
                    <div class="col-span-full py-14 text-center opacity-40 font-bold" style="color: var(--text-primary);">{{__('messages.no_products')}}</div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $catalogo->products->links() }}
            </div>
        </div>
    </main>

     
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
    </style>
</div>
    <footer class="flex items-center justify-center gap-2 px-4 py-2 text-center text-xs" style="background-color: {{ $catalogo->theme->bg_color ?? '#ffffff' }}; color: {{ isDarkColor($bgColor) ? '#ffffff' : '#000000' }};">
        <span class="leading-none">Powered by</span>
        <a href="{{ url()->to('https://tucatalogolat.lat') }}" class="inline-flex items-center gap-1 font-bold leading-none hover:underline">
            <span>tucatalogolat.lat</span>
            <img src="{{ asset('imgs/icono.ico') }}" alt="TuCatalogo.Lat" class="h-4 w-4 object-contain">
        </a>
    </footer>

</div>

