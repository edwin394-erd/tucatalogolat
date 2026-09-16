<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'catalogo_id',
        'user_id',
        'session_id',
        'customer_name',
        'customer_phone',
        'customer_notes',
        'total',
        'status',
    ];

    public function catalogo()
    {
        return $this->belongsTo(Catalogo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
