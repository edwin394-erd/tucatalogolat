<div class="space-y-6 p-6 bg-white rounded-xl shadow-lg">
    <div>
        <h2 class="text-xl font-semibold">Debug Livewire Temp Upload</h2>
        <p class="text-sm text-gray-500">Prueba la carga temporal de Livewire y la generación de temporaryUrl().</p>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Selecciona imagen</label>
            <input type="file" wire:model="image" accept="image/*" class="block w-full text-sm text-gray-700" />
            @error('image')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <p class="text-sm font-medium text-gray-700">Estado</p>
            <div class="rounded-lg bg-gray-100 p-4 text-sm text-gray-700">
                <p><strong>temporaryUrl Disponible:</strong> {{ $tempUrl ? 'sí' : 'no' }}</p>
                <p><strong>Nombre original:</strong> {{ $fileInfo['original_name'] ?? 'n/a' }}</p>
                <p><strong>MIME:</strong> {{ $fileInfo['mime_type'] ?? 'n/a' }}</p>
                <p><strong>Tamaño:</strong> {{ isset($fileInfo['size']) ? $fileInfo['size'] . ' bytes' : 'n/a' }}</p>
                <p><strong>Real path:</strong> {{ $fileInfo['real_path'] ?? 'n/a' }}</p>
                <p><strong>Real path exists:</strong> {{ $fileInfo['real_path_exists'] ?? 'n/a' }}</p>
                <p><strong>Error:</strong> {{ $debugInfo['error'] ?? 'ninguno' }}</p>
            </div>
        </div>
    </div>

    @if($tempUrl)
        <div class="rounded-xl bg-gray-50 p-4">
            <h3 class="font-semibold mb-2">Preview temporal</h3>
            <img src="{{ $tempUrl }}" alt="Preview temp" class="max-w-full rounded shadow" />
        </div>
    @endif

    <div class="rounded-xl bg-gray-50 p-4">
        <h3 class="font-semibold mb-2">Debug de directorio temporal</h3>
        <div class="grid gap-2 text-sm text-gray-700">
            <p><strong>Livewire temp disk:</strong> {{ $debugInfo['livewire_temp_disk'] ?? 'n/a' }}</p>
            <p><strong>Livewire temp directory:</strong> {{ $debugInfo['livewire_temp_directory'] ?? 'n/a' }}</p>
            <p><strong>Ruta temporal:</strong> {{ $debugInfo['temp_path'] ?? 'n/a' }}</p>
            <p><strong>Directorio temporal existe:</strong> {{ $debugInfo['temp_dir_exists'] ?? 'n/a' }}</p>
            <p><strong>Directorio temporal escribible:</strong> {{ $debugInfo['temp_dir_writable'] ?? 'n/a' }}</p>
            <p><strong>public/storage existe:</strong> {{ $debugInfo['public_storage_exists'] ?? 'n/a' }}</p>
            <p><strong>public/storage es symlink:</strong> {{ $debugInfo['public_storage_is_link'] ?? 'n/a' }}</p>
            <p><strong>storage/app/public existe:</strong> {{ $debugInfo['storage_public_exists'] ?? 'n/a' }}</p>
            <p><strong>storage/app/public escribible:</strong> {{ $debugInfo['storage_public_writable'] ?? 'n/a' }}</p>
        </div>
    </div>
</div>
