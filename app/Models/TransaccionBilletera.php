<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaccionBilletera extends Model
{
    public $timestamps = false;

    protected $table = 'transacciones_billetera';

    protected $fillable = [
        'user_id',
        'tipo',
        'descripcion',
        'monto',
        'referencia_id',
        'referencia_tipo',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
