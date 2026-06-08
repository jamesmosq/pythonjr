<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogroUsuario extends Model
{
    public $timestamps = false;

    protected $table = 'logros_usuario';

    protected $fillable = [
        'user_id',
        'logro_id',
        'desbloqueado_at',
    ];

    protected function casts(): array
    {
        return [
            'desbloqueado_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logro(): BelongsTo
    {
        return $this->belongsTo(Logro::class);
    }
}
