<section class="bg-gradient-to-br from-yellow-50 to-indigo-100 inset-shadow-sm min-h-[calc(100dvh-4rem)] px-3 py-6 sm:px-6 sm:py-10 md:px-10 flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-lg shadow border border-gray-300">
        <div class="p-5 space-y-5 sm:p-8 sm:space-y-6">
            <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">Crear nueva contraseña</h1>
            <form class="space-y-4" wire:submit.prevent="resetPassword" novalidate>
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Correo electrónico</label>
                    <input type="email" wire:model.blur="email" name="email" id="email" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 rounded-lg focus:ring-gray-600 focus:border-gray-600 block w-full p-2.5" autocomplete="email" required>
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Nueva contraseña</label>
                    <input type="password" wire:model.blur="password" name="password" id="password" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 rounded-lg focus:ring-gray-600 focus:border-gray-600 block w-full p-2.5" autocomplete="new-password" required>
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Confirmar contraseña</label>
                    <input type="password" wire:model.blur="password_confirmation" name="password_confirmation" id="password_confirmation" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 rounded-lg focus:ring-gray-600 focus:border-gray-600 block w-full p-2.5" autocomplete="new-password" required>
                </div>
                <button type="submit" class="w-full text-white bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Actualizar contraseña</button>
            </form>
        </div>
    </div>
</section>
