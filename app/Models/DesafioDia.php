<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DesafioDia extends Model
{
    public $timestamps = false;

    protected $table = 'desafios_dia';

    protected $fillable = [
        'ejercicio_id',
        'fecha',
        'recompensa',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }

    public function ejercicio(): BelongsTo
    {
        return $this->belongsTo(Ejercicio::class);
    }
}
