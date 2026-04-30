<div>
    <h2 class="text-2xl font-bold text-gray-700 mb-4">{{ $ItemId ? 'Editar Subscripcion' : 'Crear Subscripcion' }}</h2>
        <hr class="border-gray-400 my-4">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="user_id" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">Usuario</label>
                        <select id="user_id" wire:model="user_id" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-600 focus:border-gray-600 block w-full p-2.5 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-gray-500 gg:focus:border-gray-500">
                            <option value="">Selecciona un usuario</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="user_id" />
                    </div>

                    <div class="col-span-2">
                        <label for="plan_id" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">Plan</label>
                        <select id="plan_id" wire:model="plan_id" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-600 focus:border-gray-600 block w-full p-2.5 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-gray-500 gg:focus:border-gray-500">
                            <option value="">Selecciona un plan</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="plan_id" />
                    </div>


                    <div class="col-span-1">
                        <label for="starts_at" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">Fecha de Inicio</label>
                        <input type="date" id="starts_at" wire:model="fecha_de_inicio" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-600 focus:border-gray-600 block w-full p-2.5 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-gray-500 gg:focus:border-gray-500">
                        <x-input-error for="fecha_de_inicio" />
                    </div>

                    <div class="col-span-1">
                        <label for="expires_at" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">Fecha de Corte</label>
                        <input type="date" id="expires_at" wire:model="fecha_de_corte" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-600 focus:border-gray-600 block w-full p-2.5 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-gray-500 gg:focus:border-gray-500">
                        <x-input-error for="fecha_de_corte" />
                    </div>

                </div>
               
                <button wire:click="save" class="text-white inline-flex items-center bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center gg:bg-gray-600 gg:hover:bg-gray-700 gg:focus:ring-gray-800">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Guardar Subscripción
                </button>
            
</div>
