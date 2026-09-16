<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'design_configured',
        'sucursal',
    ];

    public function isConfigurationComplete(): bool
    {
        return filled($this->name)
            && filled($this->description)
            && filled($this->logo_url)
            && filled($this->banner_url)
            && filled($this->plantilla_id)
            && filled($this->theme_id)
            && $this->design_configured;
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

    public function visits()
    {
        return $this->hasMany(CatalogVisit::class);
    }

    public static function generateHandle(string $name): string
    {
        $handle = Str::slug($name, '');

        return $handle !== '' ? $handle : Str::random(8);
    }

    public static function normalizeHandle(string $value): string
    {
        return strtolower(preg_replace('/\s+/', '', (string) $value));
    }

    public static function resolveByName(string $name): ?self
    {
        $raw = trim((string) $name);

        if ($raw === '') {
            return null;
        }

        $normalizedName = self::normalizeHandle($raw);
        $generatedHandle = self::generateHandle($raw);

        return self::query()
            ->where(function ($query) use ($generatedHandle, $normalizedName) {
                $query->where('name_handle', $generatedHandle)
                    ->orWhereRaw('LOWER(REPLACE(name_handle, " ", "")) = ?', [$normalizedName])
                    ->orWhereRaw('LOWER(REPLACE(name, " ", "")) = ?', [$normalizedName]);
            })
            ->first();
    }
}
