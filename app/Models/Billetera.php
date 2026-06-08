<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Billetera extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'saldo_total',
        'saldo_pendiente',
        'saldo_pagado',
    ];

    protected $table = 'billetera';

    protected function casts(): array
    {
        return [
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
