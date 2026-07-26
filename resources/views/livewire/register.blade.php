
<section class="bg-gradient-to-br from-yellow-50 to-indigo-100 inset-shadow-sm md:p-10  ">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
            <x-logo /><br>
            <div class="w-full bg-white  rounded-lg shadow-xl border border-gray-300 mx-0 md:mt-0 sm:max-w-xl xl:==p-0 ">
                    <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                            <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-700 md:text-2xl">
                                    {{ __('messages.create_account') }}
                            </h1>
                            <form class="space-y-4 md:space-y-2" wire:submit.prevent="register" novalidate>
                                 <div>
                                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">{{ __('messages.catalog_name_business') }}</label>
                                            <input type="text" wire:model.blur="name" name="name" id="name" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="{{ __('messages.catalog_placeholder') }}" required="">
                                            @error('name')
                                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                    </div>
                                    <div>
                                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">{{ __('messages.your_email') }}</label>
                                            <input type="email" wire:model.blur="email" name="email" id="email" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="{{ __('messages.email_placeholder') }}" required="">
                                            @error('email')
                                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                    </div>
                                    <div class="flex gap-3">

                                            <div class="w-1/2">
                                                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900">{{ __('messages.password') }}</label>
                                                    <input type="password" wire:model.blur="password" name="password" id="password" placeholder="••••••••" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required="">
                                                    @error('password')
                                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                                    @enderror
                                            </div>
                                            <div class="w-1/2">
                                                    <label for="confirm-password" class="block mb-2 text-sm font-medium text-gray-900">Confirmar contraseña</label>
                                                    <input type="password" wire:model.blur="password_confirmation" name="confirm-password" id="confirm-password" placeholder="••••••••" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required="">
                                                    @error('confirm-password')
                                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                                    @enderror
                                            </div>
                                    </div>
                                <div class="flex gap-3">
                                        <div class="w-1/2">
                                                <label for="country" class="block mb-2 text-sm font-medium text-gray-900">País</label>
                                             @php
                                                        $countries = [
                                                                'Argentina', 'Bolivia', 'Brasil', 'Canadá', 'Chile', 'Colombia',
                                                                'Costa Rica', 'Cuba', 'Ecuador', 'El Salvador', 'España',
                                                                'Estados Unidos', 'Guatemala', 'Honduras', 'México',
                                                                'Nicaragua', 'Panamá', 'Paraguay', 'Perú', 'Puerto Rico',
                                                                'República Dominicana', 'Uruguay', 'Venezuela', 'Otro'
                                                        ];
                                                @endphp

                                                <x-select-busca
                                                        :options="$countries" 
                                                        placeholder="Selecciona un país" 
                                                        wireModel="country"
                                                />
                                                @error('country')
                                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                                @enderror
                                        </div>
                                        <div class="w-1/2">
                                                <label for="city" class="block mb-2 text-sm font-medium text-gray-900">Ciudad</label>
                                                <input type="text" wire:model.blur="city" name="city" id="city" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Ciudad" required="">
                                                @error('city')
                                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                                @enderror
                                        </div>
                                </div>
                                <div>
                                        <label for="address" class="block mb-2 text-sm font-medium text-gray-900">Dirección</label>
                                        <input type="text" wire:model.blur="address" name="address" id="address" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="Dirección" required="">
                                        @error('address')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="flex flex-col gap-2">
    <label for="area-code" class="block text-sm font-medium text-gray-900">Código de Área y Teléfono</label>
    
    <div class="flex gap-2">
        
<div
        x-data="{
                open: false,
                filter: '',
                selectedItem: @entangle('area_code'),
                countries: [
                         { name: 'Argentina', code: '+54', flag: 'ar' },
                                { name: 'Bolivia', code: '+591', flag: 'bo' },
                                { name: 'Brasil', code: '+55', flag: 'br' },
                                { name: 'Canadá', code: '+1', flag: 'ca' },
                                { name: 'Chile', code: '+56', flag: 'cl' },
                                { name: 'Colombia', code: '+57', flag: 'co' },
                                { name: 'Costa Rica', code: '+506', flag: 'cr' },
                                { name: 'Cuba', code: '+53', flag: 'cu' },
                                { name: 'Ecuador', code: '+593', flag: 'ec' },
                                { name: 'El Salvador', code: '+503', flag: 'sv' },
                                { name: 'España', code: '+34', flag: 'es' },
                                { name: 'Estados Unidos', code: '+1', flag: 'us' },
                                { name: 'Guatemala', code: '+502', flag: 'gt' },
                                { name: 'Honduras', code: '+504', flag: 'hn' },
                                { name: 'México', code: '+52', flag: 'mx' },
                                { name: 'Nicaragua', code: '+505', flag: 'ni' },
                                { name: 'Panamá', code: '+507', flag: 'pa' },
                                { name: 'Paraguay', code: '+595', flag: 'py' },
                                { name: 'Perú', code: '+51', flag: 'pe' },
                                { name: 'Puerto Rico', code: '+1', flag: 'pr' },
                                { name: 'República Dominicana', code: '+1', flag: 'do' },
                                { name: 'Uruguay', code: '+598', flag: 'uy' },
                                { name: 'Venezuela', code: '+58', flag: 've' }

                ],
                get filteredItems() {
                        return this.countries.filter(c => 
                                c.name.toLowerCase().includes(this.filter.toLowerCase()) || 
                                c.code.includes(this.filter)
                        );
                },
                get selectedData() {
                        return this.countries.find(c => c.code === this.selectedItem) || null;
                }
        }"
        x-on:click.away="open = false"
        class="relative w-1/3"
>
        <div
                x-on:click="open = !open; $nextTick(() => { if (open) $refs.searchInput.focus(); });"
                class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 cursor-pointer flex justify-between items-center"
        >
                <div class="flex items-center gap-2">
                        <template x-if="selectedData">
                                <span :class="'fi fi-' + selectedData.flag" class="rounded-sm"></span>
                        </template>
                        <span x-text="selectedData ? selectedData.code : 'Código'" 
                                  :class="!selectedItem ? 'text-gray-400' : ''"></span>
                </div>
                <svg class="w-4 h-4 text-gray-500 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
        </div>

        <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-xl max-h-60 overflow-y-auto"
                style="display: none;"
        >
                <div class="sticky top-0 z-20 bg-white p-2 border-b border-gray-200">
                        <input
                                x-ref="searchInput"
                                type="text"
                                x-model.debounce.200ms="filter"
                                placeholder="Buscar país..."
                                class="w-full p-2 text-sm bg-gray-50 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                        >
                </div>

                <ul class="py-1 relative z-10">
                        <template x-if="filteredItems.length === 0">
                                <li class="px-4 py-2 text-gray-500 text-sm">No hay resultados</li>
                        </template>
                        <template x-for="country in filteredItems" :key="country.name + country.code">
                                <li
                                        x-on:click="selectedItem = country.code; open = false; filter = '';"
                                        class="px-4 py-2 cursor-pointer hover:bg-gray-100 text-gray-900 flex items-center gap-3 transition-colors"
                                        :class="{ 'bg-blue-50': country.code === selectedItem }"
                                >
                                        <span :class="'fi fi-' + country.flag" class="flex-shrink-0 shadow-sm rounded-sm"></span>
                                        <span x-text="country.name" class="flex-1 text-sm"></span>
                                        <span x-text="country.code" class="text-gray-400 text-xs font-mono"></span>
                                </li>
                        </template>
                </ul>
        </div>
</div>

        <input type="tel" wire:model.blur="telephone" name="telephone" id="telephone" 
            class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-2/3 p-2.5" 
            placeholder="{{ __('messages.telephone') }}" pattern="[0-9]*" required>
    </div>

    @error('area_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    @error('telephone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
</div>


<br>
                                    <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="terms" aria-describedby="terms" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-100 inset-shadow-sm focus:ring-3 focus:ring-primary-300" required="">
                                            </div>
                                            
                                            <div class="ml-3 text-sm">
                                                <label for="terms" class="font-light text-gray-500">Acepto los <a class="font-medium text-primary-600 hover:underline" href="#">Términos y Condiciones</a></label>
                                            </div>
                                    </div>
                                    <br>
                                    <button type="submit" class="shadow shadow-lg w-full text-white bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Crear una cuenta</button>
                                    <p class="text-sm font-light text-gray-500">
                                            ¿Ya tienes una cuenta? <a href="{{ route('login') }}" wire:navigate class="font-medium text-primary-600 hover:underline">Inicia sesión aquí</a>
                                    </p>
                            </form>
                    </div>
            </div>
    </div>
</section>