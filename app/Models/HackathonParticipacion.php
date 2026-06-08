<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HackathonParticipacion extends Model
{
    protected $table = 'hackathon_participaciones';

    protected $fillable = [
        'hackathon_id', 'user_id', 'ejercicios_completados',
        'ejercicios_perfectos', 'recompensa_ganada', 'reporte_ia', 'finalizado_at',
    ];

    protected function casts(): array
    {
        return ['finalizado_at' => 'datetime'];
    }

    public function hackathon(): BelongsTo
    {
        return $this->belongsTo(Hackathon::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
