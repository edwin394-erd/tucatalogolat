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
    public $horario;
    public $ubicacion;
    public $ubicacion_mapa;
    public $instagram;
    public $facebook;
    public $twitter;
    public $tiktok;
    public $tema_id;
    public $primary_custom;
    public $bg_custom;
    public $secondary_custom;
    public $primary_font_custom;
    public $secondary_font_custom;

    public $custom_theme_catalogo;



       protected $messages = [
        'facebook.regex' => 'El enlace debe ser una URL válida de Facebook.',
        'instagram.regex' => 'El enlace debe ser una URL válida de Instagram.',
        'twitter.regex' => 'El enlace debe ser una URL válida de Twitter o X.',
        'tiktok.regex' => 'El enlace debe ser una URL válida de TikTok (ej. tiktok.com/@usuario).',
        'facebook.url' => 'El formato del enlace no es válido.',
        'instagram.url' => 'El formato del enlace no es válido.',
        'twitter.url' => 'El formato del enlace no es válido.',
        'tiktok.url' => 'El formato del enlace no es válido.',
        'url' => 'El formato del enlace no es válido.',
        'name.required' => 'El nombre del catálogo es obligatorio.',
        'name.string' => 'El nombre del catálogo debe ser una cadena de texto.',
        ];

    

    public function mount()
    {
        $this->catalogo = auth()->user()->catalogo;
        $this->name = $this->catalogo->name;
        $this->description = $this->catalogo->description;
        $this->banner = $this->catalogo->banner;
        $this->logo = $this->catalogo->logo;
        $this->plantilla_id = $this->catalogo->plantilla_id;
        $this->telefono_contacto = $this->catalogo->telefono_contacto;
        $this->horario = $this->catalogo->horario;
        $this->ubicacion = $this->catalogo->ubicacion;
        $this->ubicacion_mapa = $this->catalogo->ubicacion_mapa;
        $this->instagram = $this->catalogo->instagram;
        $this->facebook = $this->catalogo->facebook;
        $this->twitter = $this->catalogo->twitter;
        $this->tiktok = $this->catalogo->tiktok;
        $this->custom_theme_catalogo =  \App\Models\Theme::where('catalogo_id', $this->catalogo->id)->first() ?? null;
        $this->tema_id = $this->catalogo->theme_id;
     
        if($this->custom_theme_catalogo && $this->custom_theme_catalogo->id == $this->tema_id) {
            $this->tema_id = "custom";
        }
        $this->primary_custom = $this->custom_theme_catalogo->primary_color ?? '#000000';
        $this->bg_custom = $this->custom_theme_catalogo->bg_color ?? '#ffffff';
        $this->secondary_custom = $this->custom_theme_catalogo->secondary_color ?? '#cccccc';
        $this->primary_font_custom = $this->custom_theme_catalogo->primary_font_color ?? '#000000';
        $this->secondary_font_custom = $this->custom_theme_catalogo->secondary_font_color ?? '#000000';

       

    }


    public function render()
    {
        $catalogo = auth()->user()->catalogo;
        $plantillas = \App\Models\Plantilla::all();
        $themes = \App\Models\Theme::whereNull('catalogo_id')->get();

        $selectedPlantilla = $plantillas->find($this->plantilla_id) ?? $plantillas->first();

        if ($this->tema_id === 'custom') {
            $selectedTheme = (object) [
                'primary_color' => $this->primary_custom,
                'secondary_color' => $this->secondary_custom,
                'bg_color' => $this->bg_custom,
                'primary_font_color' => $this->primary_font_custom,
                'secondary_font_color' => $this->secondary_font_custom,
                'name' => __('messages.custom'),
            ];
        } else {
            $selectedTheme = $themes->find($this->tema_id) ?? $themes->first();
        }
    
        return view('livewire.configuracion', compact('catalogo', 'plantillas', 'themes', 'selectedPlantilla', 'selectedTheme'))
            ->extends('layouts.auth2')
            ->section('content');
    }

    public function saveChanges()
{

    if($this->tema_id === 'custom') {
        $this->saveCustomColors();
        $this->custom_theme_catalogo = \App\Models\Theme::where('catalogo_id', $this->catalogo->id)->first();
        $this->tema_id = $this->custom_theme_catalogo->id;
    }

 
    
$this->validate([
    'name' => 'required|string|max:255|unique:catalogos,name,' . $this->catalogo->id,
    'description' => 'nullable|string|max:1000',
    'plantilla_id' => 'required|exists:plantillas,id',
    'telefono_contacto' => 'nullable|string|max:20',
    'tema_id' => 'nullable|exists:themes,id',
    'facebook' => ['nullable', 'url'],          
    'instagram' => ['nullable', 'url'],
    'twitter' => ['nullable', 'url'],
    'tiktok' => ['nullable', 'url'],
]);

    // Asignar valores al modelo antes de guardar
    $this->catalogo->update([
        'name' => $this->name,
        'description' => $this->description,
        'horario' => $this->horario,
        'ubicacion' => $this->ubicacion,
        'plantilla_id' => $this->plantilla_id,
        'telefono_contacto' => $this->telefono_contacto,
        'theme_id' => $this->tema_id,
        'ubicacion_mapa' => $this->ubicacion_mapa,
        'instagram' => $this->instagram,
        'facebook' => $this->facebook,
        'twitter' => $this->twitter,
        'tiktok' => $this->tiktok,
    ]);

   
    session()->flash('message', __('messages.settings_updated'));
    return redirect()->route('configuracion');
}

public function selectTemplate($templateId)
{
    $this->plantilla_id = $templateId;
    $this->catalogo->plantilla_id = $templateId;
}

public function selectTheme($themeId)
{
    $this->tema_id = $themeId;
}

public function selectCustomTheme()
{
    $this->tema_id = 'custom';
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

    session()->flash('message', __('messages.banner_updated'));
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

    session()->flash('message', __('messages.logo_updated'));
    $this->redirectRoute('configuracion');
}


public function saveCustomColors()
{
    
    $this->validate([
        'primary_custom' => 'required|string|max:7',
        'bg_custom' => 'required|string|max:7',
        'secondary_custom' => 'required|string|max:7',
        'primary_font_custom' => 'required|string|max:7',
        'secondary_font_custom' => 'required|string|max:7',
    ]);

    \App\Models\Theme::updateOrCreate(
        ['id' => $this->custom_theme_catalogo->id ?? null],
        [
            'name' => 'Personalizado',
            'catalogo_id' => $this->catalogo->id,
            'primary_color' => $this->primary_custom,
            'bg_color' => $this->bg_custom,
            'secondary_color' => $this->secondary_custom,
            'primary_font_color' => $this->primary_font_custom,
            'secondary_font_color' => $this->secondary_font_custom,
        ]
    );
    session()->flash('message', __('messages.custom_colors_saved'));
    return redirect()->route('configuracion');
}

}
