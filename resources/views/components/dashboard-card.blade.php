@props(['title', 'content' => null, 'value' => null, 'link' => null, 'icon' => null])
@php
    $icons = [
        'users' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-1a3 3 0 00-3-3h-1M9 20H4v-1a3 3 0 013-3h1m8-7a4 4 0 11-8 0 4 4 0 018 0zm0 0v1a4 4 0 01-4 4H9a4 4 0 01-4-4V9"/></svg>',
        'subscriptions' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-4-4h.01M5 7h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
        'plans' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-3.31 0-6 1.79-6 4v5h12v-5c0-2.21-2.69-4-6-4zm0 0V6m0 2a4 4 0 110 8 4 4 0 010-8z"/></svg>',
        'products' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0l-8 5-8-5m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6"/></svg>',
        'categories' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h6v6H4V6zm0 10h6v6H4v-6zm10-10h6v6h-6V6zm0 10h6v6h-6v-6z"/></svg>',
    ];
@endphp
<div {{ $attributes->merge(['class' => 'rounded-2xl bg-white shadow-sm ring-1 ring-gray-100 p-4 flex flex-col h-full']) }}>

    <div class="flex items-center gap-3">
        @if($icon && isset($icons[$icon]))
            <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">{!! $icons[$icon] !!}</span>
        @endif
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $title }}</p>
    </div>

    <div class="mt-2 flex-grow">
        @if($value !== null)
            <p class="text-3xl font-black text-gray-900">{{ $value }}</p>
            @if($content)
                <p class="mt-1 text-sm text-gray-500">{{ $content }}</p>
            @endif
        @else
            {{-- Compatibilidad hacia atrás: si no se pasa $value, se comporta como antes --}}
            <p class="text-base text-gray-600">{{ $content }}</p>
        @endif
    </div>

    @if($link)
        <div class="mt-3 border-t border-gray-100 pt-2">
            <a href="{{ $link }}" class="inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition">
                {{ __('messages.show_more') }}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    @endif
</div>