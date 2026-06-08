<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EjercicioResource;
use App\Http\Resources\LeccionResource;
use App\Http\Resources\ModuloResource;
use App\Models\Modulo;
use App\Models\ProgresoModulo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModuloController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $modulos = Modulo::where('activo', true)->orderBy('nivel')->orderBy('orden')->get();

        $progresos = ProgresoModulo::where('user_id', $user->id)
            ->get()
            ->keyBy('modulo_id');

        // Usuario sin ningún progreso → desbloquear el primer módulo
        if ($progresos->isEmpty() && $modulos->isNotEmpty()) {
            $primer = ProgresoModulo::firstOrCreate(
                ['user_id' => $user->id, 'modulo_id' => $modulos->first()->id],
                ['estado' => 'disponible']
            );
            $progresos->put($modulos->first()->id, $primer);
        }

        $data = $modulos->map(function (Modulo $modulo) use ($progresos) {
            $progreso = $progresos->get($modulo->id);
            $modulo->estado_usuario = $progreso?->estado ?? 'bloqueado';
            $modulo->iniciado_at_usuario = $progreso?->iniciado_at?->toISOString();
            $modulo->completado_at_usuario = $progreso?->completado_at?->toISOString();
            return new ModuloResource($modulo);
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function show(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();

        $modulo = Modulo::where('slug', $slug)->where('activo', true)->firstOrFail();

        $progreso = ProgresoModulo::firstOrCreate(
            ['user_id' => $user->id, 'modulo_id' => $modulo->id],
            ['estado' => 'bloqueado']
        );

        // Marcar como en_progreso al abrir por primera vez
        if ($progreso->estado === 'disponible') {
            $progreso->estado = 'en_progreso';
            $progreso->iniciado_at = now();
            $progreso->save();
        }

        $lecciones = LeccionResource::collection($modulo->lecciones);

        $ejercicios = $modulo->ejercicios()->with(['opciones', 'completados' => fn ($q) => $q->where('user_id', $user->id)])->get();

        $modulo->estado_usuario = $progreso->estado;
        $modulo->iniciado_at_usuario = $progreso->iniciado_at?->toISOString();
        $modulo->completado_at_usuario = $progreso->completado_at?->toISOString();

        return response()->json([
            'success' => true,
            'data' => [
                'modulo' => new ModuloResource($modulo),
                'lecciones' => $lecciones,
                'ejercicios' => EjercicioResource::collection($ejercicios),
            ],
        ]);
    }
}
