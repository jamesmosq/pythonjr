<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EjercicioOpcion extends Model
{
    public $timestamps = false;

    protected $table = 'ejercicio_opciones';

    protected $fillable = [
        'ejercicio_id',
        'texto',
        'es_correcta',
        'orden',
    ];

    protected $hidden = [
        'es_correcta',
    ];

    protected function casts(): array
    {
        return [
            'es_correcta' => 'boolean',
        ];
    }

    public function ejercicio(): BelongsTo
    {
        return $this->belongsTo(Ejercicio::class);
    }
}
