<div class="flex items-center justify-center h-[80vh] bg-gray-100">
    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        <!-- Icono de advertencia -->
        <div class="flex justify-center mb-4">
            <svg class="w-16 h-16 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        @if(Auth::check() && Auth::id() === $catalogo->user_id)
            <h1 class="text-3xl font-bold text-gray-800 mb-2">¡Tu suscripción ha expirado!</h1>
            <br>
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded" wire:click="renovarSuscripcion">
                Renovar Suscripción
            </button><br><br>
        @else
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Ups!</h1>
        @endif

        <p class="text-xl font-semibold text-gray-800 mb-2">El catálogo no está disponible en este momento.</p>
        <span class="text-gray-500">Por favor, inténtalo más tarde.</span>
    </div>
</div>