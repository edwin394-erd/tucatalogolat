
<form wire:submit.prevent="logout" method="POST" class="inline">
        <button type="submit" class="flex items-center text-white rounded-lg dark:text-white hover:bg-red-800 dark:hover:bg-red-700 group gap-3 bg-red-600 py-2 p-4">{{ __('messages.logout') }}</button>
</form>
