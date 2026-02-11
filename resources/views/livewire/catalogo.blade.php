{{-- Definimos el color desde la BD, con un valor por defecto si es nulo --}}
@php
    $primaryColor = $catalogo->primary_color ?? '#4F46E5'; // Un azul por defecto
    $bgcolor = $catalogo->background_color ?? '#F2F2F2';
@endphp

<div class="bg-gray-100 min-h-screen pb-10" style="--main-color: {{ $primaryColor }}; background-color: {{ $bgcolor }};">
    {{-- Configuración Button --}}
    @if (auth()->check() && auth()->user()->catalogo->name == $catalogo->name)
        <a wire:navigate href="{{ route('configuracion')}}" class="bg-white text-gray-800 px-4 py-2 rounded fixed top-4 left-4 shadow-lg flex items-center gap-2 z-50 border-b-2 border-[var(--main-color)]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-[var(--main-color)]">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            Configuración
        </a>
    @endif

    {{-- Banner & Logo --}}
    <div class="relative">
        @if ($catalogo->banner_url && $catalogo->logo_url)
            <div class="w-full h-48 sm:h-64 lg:h-80 overflow-hidden rounded-b-[2rem] lg:rounded-b-[3rem] shadow-lg">
                <img src="{{ asset('storage/' . $catalogo->banner_url) }}" alt="Banner" class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-105">
            </div>
            <div class="absolute left-1/2 -bottom-12 sm:-bottom-16 lg:-bottom-20 transform -translate-x-1/2">
                <div class="relative">
                    <img src="{{ asset('storage/' . $catalogo->logo_url) }}" alt="Logo" class="w-24 h-24 sm:w-32 sm:h-32 lg:w-40 lg:h-40 rounded-full border-4 border-white shadow-xl object-cover bg-white">
                </div>
            </div>
        @else
            <div class="w-full h-48 sm:h-64 bg-gradient-to-r from-gray-200 to-[var(--main-color)] opacity-20 flex items-center justify-center rounded-b-[2rem] shadow-inner italic">
                Sin banner o logo configurado
            </div>
        @endif
    </div>

    {{-- Catalog Title --}}
    <div class="mt-16 sm:mt-20 lg:mt-24 mb-8 text-center px-6 sm:px-12 lg:px-20">
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-800 mb-2 tracking-tight">{{ $catalogo->name }}</h1>
        <p class="text-base sm:text-lg text-gray-500 max-w-2xl mx-auto leading-relaxed line-clamp-3">{{ $catalogo->description }}</p>
    </div>

    {{-- Search --}}
    <div class="max-w-7xl mx-auto px-4 mb-8 md:w-1/2">
        <input type="text" wire:model.live="search" placeholder="Buscar productos..." class="bg-gray-200 inset-shadow-sm w-full p-3 rounded-2xl border border-gray-300 focus:ring-[var(--main-color)] focus:border-[var(--main-color)] text-gray-900" />
    </div>

    {{-- Categorías --}}
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center gap-3 overflow-x-auto py-4 no-scrollbar">
            <button
                type="button"
                wire:click="clearCategoryFilter"
                class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-full border transition-colors duration-150
                       {{ (isset($selectedCategory) && $selectedCategory === null) ? 'bg-[var(--main-color)] text-white border-[var(--main-color)]' : 'bg-white text-gray-800 border-gray-200 hover:bg-gray-50' }}">
                Todas
            </button>

            @foreach ($catalogo->categories as $category)
                <button
                    type="button"
                    wire:click="filterByCategory({{ $category->id }})"
                    class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-full border transition-colors duration-150
                           {{ (isset($selectedCategory) && $selectedCategory == $category->id) ? 'bg-[var(--main-color)] text-white border-[var(--main-color)]' : 'bg-white text-gray-800 border-gray-200 hover:bg-gray-50' }}">
                    <span class="truncate max-w-[10rem] text-sm">{{ $category->name }}</span>
                </button>
            @endforeach
        </div>
    </div>

    {{-- Productos --}}
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-700 mb-6">Productos</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse ($catalogo->products as $item)
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 flex flex-col items-stretch overflow-hidden">
                    <a href="#" class="block">
                        <img class="w-full h-48 object-cover" src="{{ asset('storage/' . $item->fotos[0]->url) }}" alt="{{ $item->name }}" />
                    </a>
                    <div class="p-5 flex flex-col flex-1">
                        <h5 class="text-lg font-semibold text-gray-900 mb-2 truncate">{{ $item->name }}</h5>
                        <p class="text-gray-600 text-sm mb-4 flex-1">{{ Str::limit($item->description, 80) }}</p>
                        <div class="flex items-center justify-between mt-auto">
                            <span class="text-2xl font-bold text-gray-900">${{ $item->price }}</span>
                            <div class="flex gap-2">
                                <a href="https://wa.me/{{$catalogo->telefono_contacto}}?text={{ urlencode('Hola! Me interesa: ' . $item->name) }}" target="_blank" class="text-white bg-green-600 hover:bg-green-700 p-2.5 rounded-lg transition-colors">
                                    <svg fill="#fff" width="20px" height="20px" viewBox="0 0 32 32"><path d="M26.576 5.363c-2.69-2.69-6.406-4.354-10.511-4.354-8.209 0-14.865 6.655-14.865 14.865 0 2.732 0.737 5.291 2.022 7.491l-0.038-0.070-2.109 7.702 7.879-2.067c2.051 1.139 4.498 1.809 7.102 1.809h0.006c8.209-0.003 14.862-6.659 14.862-14.868 0-4.103-1.662-7.817-4.349-10.507zM16.062 28.228h-0.005c-2.319 0-4.489-0.64-6.342-1.753l-0.451-0.267-4.675 1.227 1.247-4.559-0.294-0.467c-1.185-1.862-1.889-4.131-1.889-6.565 0-6.822 5.531-12.353 12.353-12.353s12.353 5.531 12.353 12.353c0 6.822-5.53 12.353-12.353 12.353z"></path></svg>
                                </a>
                                <a href="#" class="text-white bg-[var(--main-color)] hover:opacity-90 p-2.5 rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-400 py-10">No hay productos disponibles.</div>
            @endforelse
        </div>
    </div>
</div>