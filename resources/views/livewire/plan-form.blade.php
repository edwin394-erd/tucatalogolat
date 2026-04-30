<form wire:submit="save" class="space-y-6">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">{{ __('messages.name') }}</label>
        <input type="text" wire:model="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">{{ __('messages.description') }}</label>
        <textarea wire:model="description" id="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="price" class="block text-sm font-medium text-gray-700">{{ __('messages.price') }}</label>
        <input type="number" step="0.01" wire:model="price" id="price" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="max_products" class="block text-sm font-medium text-gray-700">{{ __('messages.max_products') }}</label>
        <input type="number" wire:model="max_products" id="max_products" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        @error('max_products') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="duration_in_days" class="block text-sm font-medium text-gray-700">{{ __('messages.duration_days') }}</label>
        <input type="number" wire:model="duration_in_days" id="duration_in_days" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        @error('duration_in_days') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">{{ __('messages.active') }}</label>
        <input type="checkbox" wire:model="is_active" id="is_active" class="mt-1">
    </div>

    <div class="flex justify-end">
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
            {{ $ItemId ? __('messages.update') : __('messages.create') }}
        </button>
    </div>
</form>