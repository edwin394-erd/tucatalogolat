<div>
    <h2 class="text-2xl font-bold text-gray-700 mb-4">{{ $ItemId ? __('messages.edit_category') : __('messages.create_category') }}</h2>
        <hr class="border-gray-400 my-4">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">{{ __('messages.name') }}</label>
                        <input type="text" name="name" id="name" wire:model="name" class="bg-gray-100 inset-shadow-sm border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-600 focus:border-gray-600 block w-full p-2.5 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-gray-500 gg:focus:border-gray-500" placeholder="{{ __('messages.category_name_placeholder') }}" required="">
                        <x-input-error for="name" />
                    </div>

                    <div class="col-span-2">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 gg:text-white">{{ __('messages.description') }}</label>
                        <textarea id="description" rows="4" wire:model="description" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-100 inset-shadow-sm rounded-lg border border-gray-300 focus:ring-gray-500 focus:border-gray-500 gg:bg-gray-600 gg:border-gray-500 gg:placeholder-gray-400 gg:text-white gg:focus:ring-gray-500 gg:focus:border-gray-500" placeholder="{{ __('messages.category_description_placeholder') }}"></textarea>
                        <x-input-error for="description" />
                    </div>
                </div>
                <br>
                <button wire:click="save" class="text-white inline-flex items-center bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center gg:bg-gray-600 gg:hover:bg-gray-700 gg:focus:ring-gray-800">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    {{ __('messages.save_category') }}
                </button>
            
</div>
