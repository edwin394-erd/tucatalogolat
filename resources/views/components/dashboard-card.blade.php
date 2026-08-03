@props(['title', 'content', 'link' => null])
<div {{ $attributes->merge(['class' => 'shadow-xl rounded-lg bg-white flex flex-col h-full']) }}>
    <div class="px-4 py-2 bg-white rounded-t-lg border-b border-gray-100">
        <h2 class="text-xl text-gray-700 font-semibold">{{ $title }}</h2>
    </div>

    <div class="px-6 flex flex-col flex-grow">
        <h2 class="text-gray-600 text-xl pt-6 mb-4 flex-grow">{{ $content }}</h2>
        <div class="flex justify-end mt-auto">
            <a href="{{ $link ?? '#' }}" class="text-white text-sm rounded-lg bg-indigo-600 mb-6 p-2 hover:bg-indigo-700 transition">{{ __('messages.show_more') }}</a>
        </div>
    </div>
</div>
