<section class="bg-gradient-to-br from-yellow-50 to-indigo-100 inset-shadow-sm min-h-[calc(100dvh-4rem)] px-3 py-6 sm:px-6 sm:py-10 md:px-10 flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-lg shadow border border-gray-300">
        <div class="p-5 space-y-5 sm:p-8 sm:space-y-6">
            <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">Recuperar contraseña</h1>

            @if ($sent)
                <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                    Si existe una cuenta con ese correo, recibirás un enlace para crear una nueva contraseña.
                </div>
                <a href="{{ route('login') }}" wire:navigate class="block w-full text-center text-sm font-medium text-gray-600 hover:underline">Volver a iniciar sesión</a>
            @else
                <p class="text-sm text-gray-600">Escribe tu correo y te enviaremos un enlace para restablecerla.</p>
                <form class="space-y-4" wire:submit.prevent="sendResetLink" novalidate>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Correo electrónico</label>
                        <input type="email" wire:model.blur="email" name="email" id="email" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 rounded-lg focus:ring-gray-600 focus:border-gray-600 block w-full p-2.5" autocomplete="email" required>
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="w-full text-white bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Enviar enlace</button>
                    <a href="{{ route('login') }}" wire:navigate class="block text-center text-sm font-medium text-gray-600 hover:underline">Volver a iniciar sesión</a>
                </form>
            @endif
        </div>
    </div>
</section>
