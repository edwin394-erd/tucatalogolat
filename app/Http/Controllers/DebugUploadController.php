<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DebugUploadController extends Controller
{
    public function show()
    {
        $publicStorageLink = public_path('storage');
        $storagePublicDir = storage_path('app/public');
        $debugDir = storage_path('app/public/debug');

        $debugInfo = [
            'app_url' => config('app.url'),
            'default_filesystem' => config('filesystems.default'),
            'public_disk_url' => config('filesystems.disks.public.url'),
            'disk_root' => Storage::disk('public')->path(''),
            'storage_public_directory' => $storagePublicDir,
            'public_storage_link' => $publicStorageLink,
            'public_storage_is_link' => is_link($publicStorageLink),
            'public_storage_exists' => file_exists($publicStorageLink),
            'storage_public_exists' => is_dir($storagePublicDir),
            'storage_public_writable' => is_writable($storagePublicDir),
            'public_storage_writable' => file_exists($publicStorageLink) ? is_writable($publicStorageLink) : false,
            'public_storage_link_target' => is_link($publicStorageLink) ? readlink($publicStorageLink) : null,
            'debug_dir' => $debugDir,
            'debug_dir_exists' => is_dir($debugDir),
            'debug_dir_writable' => is_dir($debugDir) ? is_writable($debugDir) : false,
        ];

        return view('debug-upload', compact('debugInfo'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:4096',
        ]);

        $file = $request->file('image');

        if (! $file || ! $file->isValid()) {
            return redirect()->route('debug.upload')->with('error', 'El archivo cargado no es válido o la carga falló en el servidor.');
        }

        $filename = 'debug/' . uniqid() . '.' . $file->getClientOriginalExtension();

        try {
            $disk = Storage::disk('public');
            $diskRoot = $disk->path('');
            $debugDir = $disk->path('debug');
            $storagePublicDir = storage_path('app/public');
            $publicStorageLink = public_path('storage');

            $checks = [
                'disk_root_exists' => is_dir($diskRoot),
                'disk_root_writable' => is_writable($diskRoot),
                'storage_public_exists' => is_dir($storagePublicDir),
                'storage_public_writable' => is_writable($storagePublicDir),
                'public_storage_exists' => file_exists($publicStorageLink),
                'public_storage_is_link' => is_link($publicStorageLink),
                'public_storage_writable' => file_exists($publicStorageLink) ? is_writable($publicStorageLink) : false,
            ];

            Log::info('DebugUpload: pre-upload checks', $checks);

            if (! $checks['disk_root_exists']) {
                return redirect()->route('debug.upload')->with('error', 'No existe el directorio raíz del disco público: ' . $diskRoot);
            }

            if (! $checks['disk_root_writable']) {
                return redirect()->route('debug.upload')->with('error', 'El directorio del disco público no es escribible: ' . $diskRoot);
            }

            $basename = basename($filename);
            $storedPath = $disk->putFileAs('debug', $file, $basename);

            if (! $storedPath) {
                Log::error('DebugUpload: putFileAs returned false', [
                    'disk_root' => $diskRoot,
                    'requested_path' => 'debug/' . $basename,
                ]);

                return redirect()->route('debug.upload')->with('error', 'No se pudo guardar el archivo en el disco público. Comprueba permisos y enlace simbólico de storage.');
            }

            $path = 'debug/' . $basename;
            $fullPath = $disk->path($path);
            $existsAfter = file_exists($fullPath);

            Log::info('DebugUpload: upload result', [
                'stored_path' => $storedPath,
                'full_path' => $fullPath,
                'exists_after' => $existsAfter,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getClientMimeType(),
                'original_name' => $file->getClientOriginalName(),
            ]);

            if (! $existsAfter) {
                return redirect()->route('debug.upload')->with('error', 'El archivo no se encontró después de guardarlo en la ruta: ' . $fullPath);
            }
        } catch (\Throwable $e) {
            Log::error('DebugUpload: exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('debug.upload')->with('error', 'Error al guardar el archivo: ' . $e->getMessage());
        }

        $url = Storage::disk('public')->url($path);

        return redirect()->route('debug.upload')->with([
            'message' => 'Imagen subida correctamente.',
            'uploaded_path' => $path,
            'uploaded_url' => $url,
        ]);
    }
}
