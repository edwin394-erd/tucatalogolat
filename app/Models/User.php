<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    protected static function booted(): void
    {
        static::deleting(function (self $user): void {
            DB::transaction(function () use ($user): void {
                $catalogo = $user->catalogo()->with('products', 'products.fotos')->first();

                $user->subscriptions()->delete();

                if (! $catalogo) {
                    return;
                }

                foreach ($catalogo->products as $product) {
                    $product->delete();
                }

                $catalogo->themes()->delete();
                Storage::disk('public')->delete(array_filter([
                    $catalogo->logo_url,
                    $catalogo->banner_url,
                ]));

                $catalogo->delete();
            });
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'country',
        'city',
        'address',
        'telephone',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function catalogo()
    {
        return $this->hasOne(Catalogo::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
