<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeccionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'orden' => $this->orden,
            'tipo' => $this->tipo,
            'titulo' => $this->titulo,
            'contenido' => $this->contenido,
            'lenguaje' => $this->lenguaje,
        ];
    }
}
