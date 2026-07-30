<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

            if (! $disk->putFileAs('debug', $file, $basename)) {
                return redirect()->route('debug.upload')->with('error', 'No se pudo guardar el archivo en el disco público. Comprueba permisos y enlace simbólico de storage.');
            }
        } catch (\Throwable $e) {
            return redirect()->route('debug.upload')->with('error', 'Error al guardar el archivo: ' . $e->getMessage());
        }

        $path = 'debug/' . basename($filename);
        $url = Storage::disk('public')->url($path);

        return redirect()->route('debug.upload')->with([
            'message' => 'Imagen subida correctamente.',
            'uploaded_path' => $path,
            'uploaded_url' => $url,
        ]);
    }
}
