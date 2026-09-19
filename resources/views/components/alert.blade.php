@php
    $alertKey = session()->has('message') ? 'alert-' . md5((string) session('message') . microtime(true)) : 'alert-empty';
@endphp

<div
    wire:key="{{ $alertKey }}"
    x-data="{ show: {{ session()->has('message') ? 'true' : 'false' }} }"
    x-init="show = true; setTimeout(() => show = false, 5000)"
    x-show="show"
    x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="opacity-0 translate-x-full"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-300 transform"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-full"
    class="fixed top-5 right-5 z-[100] flex flex-col items-end space-y-4"
>
    @if (session()->has('message'))
        @if ($attributes['alert_type'] === 'success')
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg max-w-xs w-full relative" role="alert">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="text-gray-900 dark:text-white font-semibold flex-1">{{ session('message') }}</span>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-400 focus:outline-none">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <div class="absolute bottom-0 left-0 h-1 bg-green-500 rounded-b-lg animate-progress-bar"></div>
            </div>
        @elseif ($attributes['alert_type'] === 'error')
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg max-w-xs w-full relative" role="alert">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                    <span class="text-gray-900 dark:text-white font-semibold flex-1">{{ session('message') }}</span>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-400 focus:outline-none">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <div class="absolute bottom-0 left-0 h-1 bg-red-500 rounded-b-lg animate-progress-bar"></div>
            </div>
        @elseif($attributes['alert_type'] === 'info')
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg max-w-xs w-full relative" role="alert">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <span class="text-gray-900 dark:text-white font-semibold flex-1">{{ session('message') }}</span>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-400 focus:outline-none">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <div class="absolute bottom-0 left-0 h-1 bg-blue-500 rounded-b-lg animate-progress-bar"></div>
            </div>
        @endif
    @endif
</div>

<div
    x-data="{ show: false, message: '', timer: null }"
    @cart-added.window="message = $event.detail?.message || 'Producto agregado al carrito.'; show = true; clearTimeout(timer); timer = setTimeout(() => show = false, 5000)"
    x-show="show"
    x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="opacity-0 translate-x-full"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-300 transform"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-full"
    class="fixed top-5 right-5 z-[100]"
    x-cloak
>
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg max-w-xs w-full relative" role="alert">
        <div class="flex items-center space-x-3">
            <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="text-gray-900 dark:text-white font-semibold flex-1" x-text="message"></span>
            <button @click="show = false" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-400 focus:outline-none" aria-label="Cerrar alerta">&times;</button>
        </div>
        <div class="absolute bottom-0 left-0 h-1 bg-green-500 rounded-b-lg animate-progress-bar"></div>
    </div>
</div>