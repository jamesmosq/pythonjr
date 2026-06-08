<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modulo extends Model
{
    public $timestamps = false;

    protected $table = 'modulos';

    protected $fillable = [
        'nivel',
        'orden',
        'slug',
        'titulo',
        'descripcion',
        'icono',
        'dias_estimados',
        'recompensa_base',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function lecciones(): HasMany
    {
        return $this->hasMany(Leccion::class)->orderBy('orden');
    }

    public function ejercicios(): HasMany
    {
        return $this->hasMany(Ejercicio::class)->orderBy('orden');
    }

    public function progresos(): HasMany
    {
        return $this->hasMany(ProgresoModulo::class);
    }
}
