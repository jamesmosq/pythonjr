<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Racha extends Model
{
    public $timestamps = false;

    protected $table = 'rachas';

    protected $fillable = [
        'user_id',
        'dias_actuales',
        'dias_maximos',
        'ultima_actividad_at',
        'racha_3d_cobrada',
        'racha_7d_cobrada',
    ];

    protected function casts(): array
    {
        return [
            'ultima_actividad_at' => 'date',
            'racha_3d_cobrada' => 'boolean',
            'racha_7d_cobrada' => 'boolean',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
