<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DebugUploadController extends Controller
{
    public function show()
    {
        return view('debug-upload');
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
            $basename = basename($filename);
            $debugDir = $disk->path('debug');
            $existsBefore = $disk->exists('debug');

            Log::info('DebugUpload: starting upload', [
                'debug_dir' => $debugDir,
                'exists_before' => $existsBefore,
                'disk_root' => $disk->path(''),
            ]);

            $storedPath = $disk->putFileAs('debug', $file, $basename);

            if (! $storedPath) {
                Log::error('DebugUpload: putFileAs returned false', [
                    'disk_root' => $disk->path(''),
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
