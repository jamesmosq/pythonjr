<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogroResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'icono' => $this->icono,
            'tipo' => $this->tipo,
            'desbloqueado' => $this->when(isset($this->desbloqueado_at), true, false),
            'desbloqueado_at' => $this->when(isset($this->desbloqueado_at), $this->desbloqueado_at),
        ];
    }
}
