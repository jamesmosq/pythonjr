<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuloResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'icono' => $this->icono,
            'nivel' => $this->nivel,
            'orden' => $this->orden,
            'dias_estimados' => $this->dias_estimados,
            'recompensa_base' => $this->recompensa_base,
            // Estado del usuario en este módulo (se agrega desde el controller)
            'estado' => $this->when(isset($this->estado_usuario), $this->estado_usuario),
            'iniciado_at' => $this->when(isset($this->iniciado_at_usuario), $this->iniciado_at_usuario),
            'completado_at' => $this->when(isset($this->completado_at_usuario), $this->completado_at_usuario),
        ];
    }
}
