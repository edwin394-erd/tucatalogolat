<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'starts_at',
        'expires_at',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    // En app/Models/Subscription.php
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'starts_at' => 'datetime',
        ];
    }
}
