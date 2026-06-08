<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function billetera(): HasOne
    {
        return $this->hasOne(Billetera::class);
    }

    public function racha(): HasOne
    {
        return $this->hasOne(Racha::class);
    }

    public function progresoModulos(): HasMany
    {
        return $this->hasMany(ProgresoModulo::class);
    }

    public function ejerciciosCompletados(): HasMany
    {
        return $this->hasMany(EjercicioCompletado::class);
    }

    public function transacciones(): HasMany
    {
        return $this->hasMany(TransaccionBilletera::class);
    }

    public function logros(): HasMany
    {
        return $this->hasMany(LogroUsuario::class);
    }
}
