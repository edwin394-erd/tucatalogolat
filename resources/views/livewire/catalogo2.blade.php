@php
    // Mapeo de variables de base de datos con fallbacks de seguridad
    $pColor = $catalogo->PrimaryColor ?? '#2563eb';      // Botones / Acentos
    $bgColor = $catalogo->bgColor ?? '#12093B';           // Fondo general
    $sColor = $catalogo->SecundaryColor ?? '#520000';     // Sidebar y Cards
    $pFont  = $catalogo->PrimaryFontColor ?? '#FFF';   // Títulos y textos fuertes
    $sFont  = $catalogo->SecundaryFontColor ?? '#FFF'; // Descripciones y secundarios
@endphp

<div class="flex flex-col lg:flex-row min-h-screen" 
     style="--primary-btn: {{ $pColor }}; 
            --bg-main: {{ $bgColor }}; 
            --bg-card-aside: {{ $sColor }}; 
            --text-primary: {{ $pFont }}; 
            --text-secundary: {{ $sFont }}; 
            background-color: var(--bg-main);">

    {{-- Sidebar --}}
    <aside class="w-full lg:w-64 shadow-xl flex flex-col py-8 px-6 lg:fixed lg:h-full z-40 transition-colors" 
           style="background-color: var(--bg-card-aside); color: var(--text-primary);">
        
        <div class="flex flex-col items-center mb-10">
            @if ($catalogo->logo_url)
                <img src="{{ asset('storage/' . $catalogo->logo_url) }}" alt="Logo" 
                     class="w-24 h-24 rounded-full border-4 shadow-md object-cover mb-4" 
                     style="border-color: var(--primary-btn);">
            @else
                <div class="w-24 h-24 rounded-full border-4 flex items-center justify-center text-xs opacity-50 mb-4" 
                     style="border-color: var(--primary-btn); background-color: var(--bg-main);">Sin Logo</div>
            @endif
            <h2 class="text-xl font-black text-center leading-tight" style="color: var(--text-secundary);">{{ $catalogo->name }}</h2>
            <p class="text-sm text-center mt-2 opacity-80" style="color: var(--text-secundary);">{{ $catalogo->description }}</p>
        </div>

        <nav class="flex-1 overflow-y-auto no-scrollbar">
            <ul class="space-y-1">
                <li class="mb-4 opacity-50 text-xs font-bold uppercase tracking-widest" style="color: var(--text-secundary);">Categorías</li>
                <li>
                    <a href="javascript:void(0)" wire:click="filterByCategory(null)" 
                       class="flex items-center py-2 px-4 rounded-lg hover:translate-x-1"
                       style="color: var(--text-secundary); border-left: 4px solid var(--primary-btn); background-color: rgba(0,0,0,0.03);">
                       Todas
                    </a>
                </li>
                @foreach ($catalogo->categories as $categoria)
                    <li>
                        <a href="javascript:void(0)" wire:click="filterByCategory({{ $categoria->id }})" 
                           class="flex items-center py-2 px-4 rounded-lg hover:translate-x-1 hover:bg-black/5"
                           style="color: var(--text-secundary);">
                            {{ $categoria->name }}
                        </a>
                    </li>
                @endforeach
            </ul>

            @if (auth()->check() && auth()->user()->catalogo->id == $catalogo->id)
                <div class="mt-8 pt-6 border-t border-black/10">
                    <a wire:navigate href="{{ route('configuracion') }}" 
                       class="flex items-center gap-2 py-3 px-4 rounded-xl text-white bg-indigo-600 font-bold shadow-lg transition-transform active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                        </svg>
                        Ajustes
                    </a>
                </div>
            @endif
        </nav>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 lg:ml-64 p-4 lg:p-8">
        <div class="max-w-7xl mx-auto">
            {{-- Header de sección --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
                <h2 class="text-3xl font-black tracking-tight" style="color: var(--text-primary);">Nuestros Productos</h2>
                <div class="relative w-full md:w-96">
                    <input type="text" wire:model.live="search" 
                           class="w-full p-4 pl-12 rounded-2xl border-none shadow-sm focus:ring-2 outline-none" 
                           style="background-color: var(--bg-card-aside); color: var(--text-primary); --tw-ring-color: var(--primary-btn);" 
                           placeholder="¿Qué estás buscando?">
                    <svg class="absolute left-4 top-4 w-5 h-5 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <div class="mb-8">
                {{ $catalogo->products->links() }}
            </div>

            {{-- Grid de Productos --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse ($catalogo->products as $item)
                    <div class="rounded-3xl shadow-sm hover:shadow-2xl  overflow-hidden flex flex-col group border border-black/5" 
                         style="background-color: var(--bg-card-aside);">
                        
                        <div class="relative h-56 overflow-hidden">
                            <img src="{{ asset('storage/' . $item->fotos[0]->url) }}" 
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute top-4 right-4 py-1 px-3 rounded-full text-xs font-bold backdrop-blur-md bg-white/30" 
                                 style="color: var(--text-primary);">
                                En Stock
                            </div>
                        </div>

                        <div class="p-6 flex flex-col flex-1">
                            <h5 class="text-lg font-bold mb-2 line-clamp-1" style="color: var(--text-secundary);">{{ $item->name }}</h5>
                            <p class="text-sm mb-6 flex-1 line-clamp-3 leading-relaxed" style="color: var(--text-secundary);">{{ $item->description }}</p>
                            
                            <div class="flex items-center justify-between mt-auto">
                                <span class="text-2xl font-black" style="color: var(--text-secundary);">${{ number_format($item->price, 2) }}</span>
                                <div class="flex gap-2">
                                    <a href="https://wa.me/{{$catalogo->telefono_contacto}}?text={{ urlencode('Me interesa: ' . $item->name) }}" 
                                       target="_blank" 
                                       class="p-3 rounded-2xl bg-green-500 text-white hover:bg-green-600 shadow-lg transition-transform active:scale-90">
                                       <svg fill="currentColor" width="20" height="20" viewBox="0 0 32 32"><path d="M26.576 5.363c-2.69-2.69-6.406-4.354-10.511-4.354-8.209 0-14.865 6.655-14.865 14.865 0 2.732 0.737 5.291 2.022 7.491l-0.038-0.070-2.109 7.702 7.879-2.067c2.051 1.139 4.498 1.809 7.102 1.809h0.006c8.209-0.003 14.862-6.659 14.862-14.868 0-4.103-1.662-7.817-4.349-10.507zM16.062 28.228h-0.005c-2.319 0-4.489-0.64-6.342-1.753l-0.451-0.267-4.675 1.227 1.247-4.559-0.294-0.467c-1.185-1.862-1.889-4.131-1.889-6.565 0-6.822 5.531-12.353 12.353-12.353s12.353 5.531 12.353 12.353c0 6.822-5.53 12.353-12.353 12.353z"></path></svg>
                                    </a>
                                    <button class="p-3 rounded-2xl text-white shadow-lg active:scale-90 hover:opacity-90" 
                                            style="background-color: var(--primary-btn);">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center opacity-40 font-bold" style="color: var(--text-secundary);">
                        No hay productos que coincidan con tu búsqueda.
                    </div>
                @endforelse
            </div>
        </div>
    </main>
</div>