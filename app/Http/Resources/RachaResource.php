<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RachaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'dias_actuales' => $this->dias_actuales,
            'dias_maximos' => $this->dias_maximos,
            'ultima_actividad_at' => $this->ultima_actividad_at?->toDateString(),
            'activa_hoy' => $this->ultima_actividad_at?->toDateString() === now()->toDateString(),
        ];
    }
}
