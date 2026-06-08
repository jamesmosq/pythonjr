<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgresoModulo extends Model
{
    public $timestamps = false;

    protected $table = 'progreso_modulos';

    protected $fillable = [
        'user_id',
        'modulo_id',
        'estado',
        'iniciado_at',
        'completado_at',
        'bono_velocidad_aplicado',
    ];

    protected function casts(): array
    {
        return [
            'iniciado_at' => 'datetime',
            'completado_at' => 'datetime',
            'bono_velocidad_aplicado' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class);
    }
}
