<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EjercicioCompletado extends Model
{
    public $timestamps = false;

    protected $table = 'ejercicios_completados';

    protected $fillable = [
        'user_id',
        'ejercicio_id',
        'modulo_id',
        'intento',
        'respuesta_dada',
        'es_correcto',
        'es_perfecto',
        'validado_por_padre',
        'recompensa_ganada',
        'completado_at',
    ];

    protected function casts(): array
    {
        return [
            'es_correcto' => 'boolean',
            'es_perfecto' => 'boolean',
            'validado_por_padre' => 'boolean',
            'completado_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ejercicio(): BelongsTo
    {
        return $this->belongsTo(Ejercicio::class);
    }

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class);
    }
}
