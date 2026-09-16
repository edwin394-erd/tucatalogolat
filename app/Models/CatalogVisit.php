<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'catalogo_id',
        'session_id',
        'ip_address',
        'user_agent',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public function catalogo()
    {
        return $this->belongsTo(Catalogo::class);
    }
}
