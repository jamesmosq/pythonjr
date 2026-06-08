<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EjercicioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $request->user();

        $completado = $user
            ? $this->completados->where('user_id', $user->id)->first()
            : null;

        return [
            'id' => $this->id,
            'orden' => $this->orden,
            'tipo' => $this->tipo,
            'titulo' => $this->titulo,
            'enunciado' => $this->enunciado,
            'codigo_base' => $this->codigo_base,
            'es_obligatorio' => $this->es_obligatorio,
            'recompensa_ejercicio' => $this->recompensa_ejercicio,
            'recompensa_perfecto' => $this->recompensa_perfecto,
            'pista' => $this->when($completado?->intento >= 2, $this->pista),
            'opciones' => $this->when(
                $this->tipo === 'quiz_opcion',
                fn () => $this->opciones->map(fn ($o) => ['id' => $o->id, 'texto' => $o->texto, 'orden' => $o->orden])
            ),
            // Estado del usuario en este ejercicio
            'completado' => (bool) $completado,
            'es_correcto' => $completado?->es_correcto ?? false,
            'es_perfecto' => $completado?->es_perfecto ?? false,
            'validado_por_padre' => $completado?->validado_por_padre ?? false,
            'intento' => $completado?->intento ?? 0,
            'modulo_slug' => $this->whenLoaded('modulo', fn () => $this->modulo->slug),
            'modulo_titulo' => $this->whenLoaded('modulo', fn () => $this->modulo->titulo),
        ];
    }
}
