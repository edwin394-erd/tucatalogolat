<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Catalogo;

class Plantilla extends Model
{
    protected $table = 'plantillas';

    protected $fillable = [
        'name',
        'description',
        'image_url',
    ];

    public function catalogos()
    {
        return $this->hasMany(Catalogo::class);
    }
}
