@props(['title', 'content', 'link' => null, 'icon' => null])
@php
    $icons = [
        'users' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-1a3 3 0 00-3-3h-1M9 20H4v-1a3 3 0 013-3h1m8-7a4 4 0 11-8 0 4 4 0 018 0zm0 0v1a4 4 0 01-4 4H9a4 4 0 01-4-4V9"/></svg>',
        'subscriptions' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-4-4h.01M5 7h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
        'plans' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-3.31 0-6 1.79-6 4v5h12v-5c0-2.21-2.69-4-6-4zm0 0V6m0 2a4 4 0 110 8 4 4 0 010-8z"/></svg>',
        'products' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0l-8 5-8-5m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6"/></svg>',
        'categories' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h6v6H4V6zm0 10h6v6H4v-6zm10-10h6v6h-6V6zm0 10h6v6h-6v-6z"/></svg>',
    ];
@endphp
<div {{ $attributes->merge(['class' => 'shadow-xl rounded-lg bg-white flex flex-col h-full']) }}>
    <div class="px-4 py-2 bg-white rounded-t-lg border-b border-gray-100 flex items-center gap-3">
        @if($icon && isset($icons[$icon]))
            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600">{!! $icons[$icon] !!}</span>
        @endif
        <h2 class="text-xl text-gray-700 font-semibold">{{ $title }}</h2>
    </div>

    <div class="px-6 flex flex-col flex-grow">
        <h2 class="text-gray-600 text-xl pt-6 mb-4 flex-grow">{{ $content }}</h2>
        <div class="flex justify-end mt-auto">
            <a href="{{ $link ?? '#' }}" class="text-white text-sm rounded-lg bg-indigo-600 mb-6 p-2 hover:bg-indigo-700 transition">{{ __('messages.show_more') }}</a>
        </div>
    </div>
</div>
