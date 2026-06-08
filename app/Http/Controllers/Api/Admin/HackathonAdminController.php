<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hackathon;
use App\Models\User;
use App\Services\HackathonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HackathonAdminController extends Controller
{
    public function __construct(private HackathonService $hackathonService) {}

    public function index(): JsonResponse
    {
        $hackathones = Hackathon::latest()->limit(10)->get()->map(fn ($h) => [
            'id'                  => $h->id,
            'nombre'              => $h->nombre,
            'tipo'                => $h->tipo,
            'estado'              => $h->estado,
            'recompensa_base'     => $h->recompensa_base,
            'tiempo_limite_horas' => $h->tiempo_limite_horas,
            'starts_at'           => $h->starts_at,
            'ends_at'             => $h->ends_at,
            'ejercicios_count'    => $h->ejercicios()->count(),
        ]);

        return response()->json(['success' => true, 'data' => $hackathones]);
    }

    public function activar(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nombre'              => 'required|string|max:100',
            'tipo'                => 'required|in:sprint,maraton,maestro,tematico',
            'recompensa_base'     => 'required|integer|min:10000',
            'tiempo_limite_horas' => 'nullable|integer|min:1|max:168',
            'multiplicador_velocidad' => 'nullable|numeric|min:1|max:3',
            'multiplicador_perfecto'  => 'nullable|numeric|min:1|max:3',
        ]);

        // Cancelar hackathon activo anterior
        Hackathon::where('estado', 'activo')->update(['estado' => 'cancelado']);

        $estudiante = User::where('role', 'estudiante')->first();

        // Verificar que el estudiante tiene progreso suficiente
        $modulosCompletados = $estudiante?->progresoModulos()
            ->where('estado', 'completado')
            ->count() ?? 0;

        if ($modulosCompletados === 0) {
            return response()->json([
                'success' => false,
                'message' => 'El estudiante aún no ha completado ningún módulo. Completa al menos uno para activar un hackathon.',
            ], 422);
        }

        $startsAt = now();
        $endsAt   = isset($data['tiempo_limite_horas'])
            ? now()->addHours($data['tiempo_limite_horas'])
            : null;

        $hackathon = Hackathon::create([
            'creado_por'              => $request->user()->id,
            'nombre'                  => $data['nombre'],
            'tipo'                    => $data['tipo'],
            'recompensa_base'         => $data['recompensa_base'],
            'multiplicador_velocidad' => $data['multiplicador_velocidad'] ?? 1.3,
            'multiplicador_perfecto'  => $data['multiplicador_perfecto'] ?? 1.5,
            'tiempo_limite_horas'     => $data['tiempo_limite_horas'] ?? null,
            'starts_at'               => $startsAt,
            'ends_at'                 => $endsAt,
            'estado'                  => 'activo',
        ]);

        if ($estudiante) {
            $this->hackathonService->generarEjercicios($estudiante, $hackathon);
        }

        return response()->json([
            'success' => true,
            'message' => "¡Hackathon \"{$hackathon->nombre}\" activado con {$hackathon->ejercicios()->count()} ejercicios!",
            'data'    => [
                'id'               => $hackathon->id,
                'nombre'           => $hackathon->nombre,
                'tipo'             => $hackathon->tipo,
                'recompensa_base'  => $hackathon->recompensa_base,
                'ends_at'          => $hackathon->ends_at,
                'ejercicios_count' => $hackathon->ejercicios()->count(),
            ],
        ]);
    }

    public function cancelar(Hackathon $hackathon): JsonResponse
    {
        $hackathon->update(['estado' => 'cancelado']);

        return response()->json(['success' => true, 'message' => 'Hackathon cancelado.']);
    }
}
