<div>
    <h2 class="text-2xl font-bold text-gray-700 mb-4">{{ $ItemId ? __('messages.edit_discount') : __('messages.create_discount') }}</h2>
        <hr class="border-gray-400 my-4">
  <form class="p-4 md:p-5" wire:submit.prevent="save" novalidate>
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-1">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 :text-white">{{ __('messages.name') }}</label>
                        <input type="text" name="name" id="name" wire:model="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 :bg-gray-600 :border-gray-500 :placeholder-gray-400 :text-white :focus:ring-primary-500 :focus:border-primary-500" placeholder="{{ __('messages.discount_name_placeholder') }}" required="">
                        <x-input-error for="name" class="mt-2" />
                    </div>
                    <div class="col-span-1">
                        <label for="discount_percentage" class="block mb-2 text-sm font-medium text-gray-900 :text-white">{{ __('messages.discount_percentage') }}</label>
                        <input type="number" name="discount_percentage" id="discount_percentage" wire:model="discount_percentage" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 :bg-gray-600 :border-gray-500 :placeholder-gray-400 :text-white :focus:ring-primary-500 :focus:border-primary-500" placeholder="{{ __('messages.discount_percentage_placeholder') }}" required="">
                        <x-input-error for="discount_percentage" class="mt-2" />
                    </div>

                    <div class="col-span-1">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 :text-white">{{ __('messages.description') }}</label>
                        <textarea id="description" rows="4" wire:model="description" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-gray-500 focus:border-gray-500 :bg-gray-600 :border-gray-500 :placeholder-gray-400 :text-white :focus:ring-gray-500 :focus:border-gray-500" placeholder="{{ __('messages.description_placeholder') }}"></textarea>
                        <x-input-error for="description" class="mt-2" />
                    </div>

                    <div class="col-span-1">
                            <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900 :text-white">{{ __('messages.start_date') }}</label>
                            <input type="date" name="start_date" id="start_date" wire:model="start_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 :bg-gray-600 :border-gray-500 :placeholder-gray-400 :text-white :focus:ring-primary-500 :focus:border-primary-500" required="">
                            <x-input-error for="start_date" class="mt-2" />
                    </div>
                    <div class="col-span-1">
                            <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900 :text-white">{{ __('messages.expiration_date') }}</label>
                            <input type="date" name="end_date" id="end_date" wire:model="end_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 :bg-gray-600 :border-gray-500 :placeholder-gray-400 :text-white :focus:ring-primary-500 :focus:border-primary-500" required="">
                            <x-input-error for="end_date" class="mt-2" />
                     </div>
                     

                </div>
                <br>

                <button type="submit" class="text-white inline-flex items-center bg-indigo-600 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center :bg-gray-600 :hover:bg-gray-700 :focus:ring-gray-800">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    {{ __('messages.save_discount') }}
                </button>
            </form>
</div>
