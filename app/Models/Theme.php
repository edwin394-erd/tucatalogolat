<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'name',
        'primary_color',
        'secondary_color',
        'bg_color',
        'primary_font_color',
        'secondary_font_color',
        'catalogo_id',
    ];

    public function catalogos()
    {
        return $this->hasMany(Catalogo::class);
    }

   
}
