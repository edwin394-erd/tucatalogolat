<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'catalogo_id',
        'descuento_id',
    ];

    protected static function boot()
    {
        
        parent::boot();

        static::deleting(function ($product) {
            foreach ($product->fotos as $foto) {
                \Storage::disk('public')->delete($foto->url);
                $foto->delete();
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function catalogo()
    {
        return $this->belongsTo(Catalogo::class);
    }

    public function fotos()
    {
        return $this->morphMany(foto::class, 'imageable');
    }
    public function descuento()
    {
        return $this->belongsTo(Descuento::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

}
