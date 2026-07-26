<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model
{
    protected $fillable = [
        'name',
        'name_handle',
        'description',
        'user_id',
        'plantilla_id',
        'telefono_contacto',
        'ubicacion',
        'ubicacion_mapa',
        'instagram',
        'facebook',
        'twitter',
        'tiktok',
        'horario',
        'theme_id',
        'sucursal',
    ];

    public function isConfigurationComplete(): bool
    {
        return filled($this->name)
            && filled($this->description)
            && filled($this->logo_url)
            && filled($this->banner_url)
            && filled($this->plantilla_id)
            && filled($this->theme_id);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plantilla()
    {
        return $this->belongsTo(Plantilla::class);
    }
    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    
}
