<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CaategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'catalogo_id',
    ];

    public function catalogo()
    {
        return $this->belongsTo(Catalogo::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function fotos()
    {
        return $this->morphMany(foto::class, 'imageable');
    }
}
