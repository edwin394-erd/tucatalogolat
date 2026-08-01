@extends('layouts.auth2')

@section('content')
<div class="max-w-4xl mx-auto mt-8 p-6 bg-white rounded-xl shadow-lg">
    <h1 class="text-2xl font-semibold mb-4">Debug Upload</h1>

    @if(session('message'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded">
            <h3 class="font-semibold text-red-700 mb-2">Errores de validación</h3>
            <ul class="list-disc list-inside text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('debug.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label for="image" class="block text-sm font-medium text-gray-700">Imagen</label>
            <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-700" required>
            @error('image')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            Subir imagen de prueba
        </button>
    </form>

    <div class="mt-8 space-y-4">
        <div class="bg-gray-50 p-4 rounded">
            <h2 class="font-semibold mb-2">Rutas de almacenamiento</h2>
            <p><strong>APP_URL:</strong> {{ config('app.url') }}</p>
            <p><strong>FILESYSTEM_DISK:</strong> {{ config('filesystems.default') }}</p>
            <p><strong>public disk URL:</strong> {{ config('filesystems.disks.public.url') }}</p>
            <p><strong>storage/app/public:</strong> {{ storage_path('app/public') }}</p>
            <p><strong>public/storage:</strong> {{ public_path('storage') }}</p>
            <p><strong>public/storage es symlink:</strong> {{ is_link(public_path('storage')) ? 'sí' : 'no' }}</p>
            <p><strong>public/storage existe:</strong> {{ file_exists(public_path('storage')) ? 'sí' : 'no' }}</p>
            <p><strong>public/storage escribible:</strong> {{ file_exists(public_path('storage')) ? (is_writable(public_path('storage')) ? 'sí' : 'no') : 'n/a' }}</p>
            <p><strong>storage/app/public existe:</strong> {{ is_dir(storage_path('app/public')) ? 'sí' : 'no' }}</p>
            <p><strong>storage/app/public escribible:</strong> {{ is_dir(storage_path('app/public')) ? (is_writable(storage_path('app/public')) ? 'sí' : 'no') : 'n/a' }}</p>
            <p><strong>debug dir expected:</strong> {{ storage_path('app/public/debug') }}</p>
            <p><strong>debug dir exists:</strong> {{ is_dir(storage_path('app/public/debug')) ? 'sí' : 'no' }}</p>
            <p><strong>debug dir escribible:</strong> {{ is_dir(storage_path('app/public/debug')) ? (is_writable(storage_path('app/public/debug')) ? 'sí' : 'no') : 'n/a' }}</p>
            <p><strong>debug file exists (if uploaded_path):</strong> {{ session('uploaded_path') ? (file_exists(storage_path('app/public/' . session('uploaded_path'))) ? 'sí' : 'no') : 'n/a' }}</p>
        </div>

        <div class="bg-gray-50 p-4 rounded">
            <h2 class="font-semibold mb-2">Debug Livewire temp upload</h2>
            <p class="text-sm text-gray-600 mb-4">Esta sección prueba la previsualización temporal de Livewire usando <code>temporaryUrl()</code>.</p>
            @livewire('debug-temp-upload')
        </div>

        @if(session('uploaded_path'))
            <div class="bg-blue-50 p-4 rounded">
                <h2 class="font-semibold mb-2">Archivo subido</h2>
                <p><strong>Ruta guardada:</strong> {{ session('uploaded_path') }}</p>
                <p><strong>URL accesible:</strong> <a href="{{ session('uploaded_url') }}" target="_blank" class="text-blue-600 underline">{{ session('uploaded_url') }}</a></p>
                <div class="mt-4">
                    <img src="{{ session('uploaded_url') }}" alt="Imagen subida" class="max-w-full rounded shadow" />
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
