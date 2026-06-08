<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ejercicio extends Model
{
    public $timestamps = false;

    protected $table = 'ejercicios';

    protected $fillable = [
        'modulo_id',
        'orden',
        'tipo',
        'titulo',
        'enunciado',
        'codigo_base',
        'solucion',
        'respuesta_correcta',
        'es_obligatorio',
        'recompensa_ejercicio',
        'recompensa_perfecto',
        'pista',
    ];

    protected $hidden = [
        'solucion',
        'respuesta_correcta',
    ];

    protected function casts(): array
    {
        return [
            'es_obligatorio' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class);
    }

    public function opciones(): HasMany
    {
        return $this->hasMany(EjercicioOpcion::class)->orderBy('orden');
    }

    public function completados(): HasMany
    {
        return $this->hasMany(EjercicioCompletado::class);
    }

    public function esAutoVerificable(): bool
    {
        return in_array($this->tipo, ['quiz_opcion', 'quiz_texto']);
    }
}
