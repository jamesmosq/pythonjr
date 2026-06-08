<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hackathon extends Model
{
    protected $table = 'hackathones';

    protected $fillable = [
        'creado_por', 'nombre', 'tipo', 'recompensa_base',
        'multiplicador_velocidad', 'multiplicador_perfecto',
        'tiempo_limite_horas', 'starts_at', 'ends_at', 'estado',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at'   => 'datetime',
            'multiplicador_velocidad' => 'float',
            'multiplicador_perfecto'  => 'float',
        ];
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function ejercicios(): HasMany
    {
        return $this->hasMany(HackathonEjercicio::class)->orderBy('orden');
    }

    public function participaciones(): HasMany
    {
        return $this->hasMany(HackathonParticipacion::class);
    }

    public function estaActivo(): bool
    {
        if ($this->estado !== 'activo') {
            return false;
        }
        if ($this->ends_at && now()->isAfter($this->ends_at)) {
            return false;
        }
        return true;
    }

    public function tiempoRestanteSegundos(): ?int
    {
        if (! $this->ends_at) {
            return null;
        }
        return max(0, now()->diffInSeconds($this->ends_at, false));
    }
}
