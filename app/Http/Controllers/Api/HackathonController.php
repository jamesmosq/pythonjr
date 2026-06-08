<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EjercicioResource;
use App\Models\EjercicioCompletado;
use App\Services\HackathonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HackathonController extends Controller
{
    public function __construct(private HackathonService $hackathonService) {}

    public function activo(Request $request): JsonResponse
    {
        $user      = $request->user();
        $hackathon = $this->hackathonService->hackathonActivoParaUsuario($user);

        if (! $hackathon) {
            return response()->json(['success' => true, 'data' => null]);
        }

        $completadosIds = EjercicioCompletado::where('user_id', $user->id)
            ->where('hackathon_id', $hackathon->id)
            ->where('es_correcto', true)
            ->pluck('ejercicio_id')
            ->toArray();

        $ejercicios = $hackathon->ejercicios()
            ->with(['ejercicio.opciones', 'ejercicio.completados'])
            ->get()
            ->map(fn ($he) => [
                'orden'      => $he->orden,
                'completado' => in_array($he->ejercicio_id, $completadosIds),
                'ejercicio'  => new EjercicioResource($he->ejercicio),
            ]);

        $participacion = $hackathon->participaciones()->where('user_id', $user->id)->first();

        return response()->json([
            'success' => true,
            'data'    => [
                'id'                   => $hackathon->id,
                'nombre'               => $hackathon->nombre,
                'tipo'                 => $hackathon->tipo,
                'recompensa_base'      => $hackathon->recompensa_base,
                'multiplicador_velocidad' => $hackathon->multiplicador_velocidad,
                'multiplicador_perfecto'  => $hackathon->multiplicador_perfecto,
                'ends_at'              => $hackathon->ends_at,
                'tiempo_restante'      => $hackathon->tiempoRestanteSegundos(),
                'ejercicios'           => $ejercicios,
                'total'                => $ejercicios->count(),
                'completados'          => count($completadosIds),
                'finalizado'           => (bool) $participacion?->finalizado_at,
                'reporte_ia'           => $participacion?->reporte_ia,
                'recompensa_ganada'    => $participacion?->recompensa_ganada ?? 0,
            ],
        ]);
    }
}
