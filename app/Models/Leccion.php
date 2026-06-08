<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leccion extends Model
{
    public $timestamps = false;

    protected $table = 'lecciones';

    protected $fillable = [
        'modulo_id',
        'orden',
        'tipo',
        'titulo',
        'contenido',
        'lenguaje',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class);
    }
}
