<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;


use Intervention\Image\Drivers\Gd\Driver;


class Configuracion extends Component
{
        use WithFileUploads;

    public $catalogo;
    public $name;
    public $description;
    public $banner;
    public $logo;
    public $plantilla_id;
    public $telefono_contacto;

    public function mount()
    {
        $this->catalogo = auth()->user()->catalogo;
        $this->name = $this->catalogo->name;
        $this->description = $this->catalogo->description;
        $this->banner = $this->catalogo->banner;
        $this->logo = $this->catalogo->logo;
        $this->plantilla_id = $this->catalogo->plantilla_id;
        $this->telefono_contacto = $this->catalogo->telefono_contacto;
    }


    public function render()
    {
        $catalogo = auth()->user()->catalogo;
        $plantillas = \App\Models\Plantilla::all();
        return view('livewire.configuracion', compact('catalogo', 'plantillas'))
        ->extends('layouts.auth2')
        ->section('content');
    }

    public function saveChanges()
{
    // Validar propiedades locales
    $this->validate([
        'name' => 'required|string|max:255|unique:catalogos,name,' . $this->catalogo->id,
        'description' => 'nullable|string|max:1000',
        'plantilla_id' => 'required|exists:plantillas,id',
        'telefono_contacto' => 'nullable|string|max:20',
    ]);

    // Asignar valores al modelo antes de guardar
    $this->catalogo->update([
        'name' => $this->name,
        'description' => $this->description,
        'plantilla_id' => $this->plantilla_id,
        'telefono_contacto' => $this->telefono_contacto,
    ]);

    session()->flash('message', 'Configuración actualizada con éxito.');
    return redirect()->route('configuracion');
}

public function selectTemplate($templateId)
{
    // Esto es lo que le falta a tu código:
    $this->plantilla_id = $templateId; 
    
    // (Opcional) Esto también ayuda a que la UI se mantenga sincronizada
    $this->catalogo->plantilla_id = $templateId; 
}
  public function updatedBanner()
{
    $this->validate(['banner' => 'image|max:2048']);
    
    $manager = new ImageManager(new Driver());
    $path = 'banners/' . uniqid() . '.webp';
    
    $img = $manager->read($this->banner->getRealPath())
                   ->scale(width: 1200)
                   ->toWebp(80);

    Storage::disk('public')->put($path, $img);

    // Asignación directa y persistencia
    $this->catalogo->banner_url = $path;
    $this->catalogo->save();

    session()->flash('message', 'Banner actualizado con éxito.');
    $this->redirectRoute('configuracion');
}

public function updatedLogo()
{
    $this->validate(['logo' => 'image|max:2048']);

    $manager = new ImageManager(new Driver());
    $path = 'logos/' . uniqid() . '.webp';
    
    $img = $manager->read($this->logo->getRealPath())
                   ->cover(400, 400) 
                   ->toWebp(85);

    Storage::disk('public')->put($path, $img);
    
    // Asignación directa y persistencia
    $this->catalogo->logo_url = $path;
    $this->catalogo->save();

    session()->flash('message', 'Logo actualizado con éxito.');
    $this->redirectRoute('configuracion');
}
}
