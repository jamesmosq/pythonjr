<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IntentarEjercicioRequest;
use App\Models\DesafioDia;
use App\Models\EjercicioCompletado;
use App\Services\GamificacionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DesafioDiaController extends Controller
{
    public function __construct(private GamificacionService $gamificacion) {}

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $desafio = DesafioDia::where('fecha', now()->toDateString())
            ->with(['ejercicio.opciones'])
            ->first();

        if (! $desafio) {
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'No hay desafío del día hoy.',
            ]);
        }

        $completado = EjercicioCompletado::where('user_id', $user->id)
            ->where('ejercicio_id', $desafio->ejercicio_id)
            ->where('es_correcto', true)
            ->exists();

        $ejercicio = $desafio->ejercicio;
        $opciones = $ejercicio->tipo === 'quiz_opcion'
            ? $ejercicio->opciones->map(fn ($o) => ['id' => $o->id, 'texto' => $o->texto, 'orden' => $o->orden])
            : null;

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $desafio->id,
                'fecha' => $desafio->fecha->toDateString(),
                'recompensa' => $desafio->recompensa,
                'caduca_at' => now()->endOfDay()->toISOString(),
                'completado' => $completado,
                'ejercicio' => [
                    'id' => $ejercicio->id,
                    'tipo' => $ejercicio->tipo,
                    'titulo' => $ejercicio->titulo,
                    'enunciado' => $ejercicio->enunciado,
                    'codigo_base' => $ejercicio->codigo_base,
                    'opciones' => $opciones,
                ],
            ],
        ]);
    }

    public function intentar(IntentarEjercicioRequest $request): JsonResponse
    {
        $user = $request->user();

        $desafio = DesafioDia::where('fecha', now()->toDateString())
            ->with('ejercicio.opciones')
            ->first();

        if (! $desafio) {
            return response()->json(['success' => false, 'message' => 'No hay desafío hoy.'], 404);
        }

        $ejercicio = $desafio->ejercicio;
        $respuesta = trim((string) $request->input('respuesta', ''));

        $yaCompletado = EjercicioCompletado::where('user_id', $user->id)
            ->where('ejercicio_id', $ejercicio->id)
            ->where('es_correcto', true)
            ->exists();

        if ($yaCompletado) {
            return response()->json(['success' => false, 'message' => 'Ya completaste el desafío de hoy.'], 422);
        }

        $registro = EjercicioCompletado::firstOrNew([
            'user_id' => $user->id,
            'ejercicio_id' => $ejercicio->id,
        ]);
        $registro->modulo_id = $ejercicio->modulo_id;
        $registro->respuesta_dada = $respuesta;
        $registro->intento = ($registro->intento ?? 0) + 1;

        if ($ejercicio->tipo === 'quiz_opcion') {
            $opcionCorrecta = $ejercicio->opciones->firstWhere('es_correcta', true);
            $esCorrecto = $opcionCorrecta && (string) $opcionCorrecta->id === $respuesta;
        } else {
            $esCorrecto = mb_strtolower(trim($respuesta)) === mb_strtolower(trim($ejercicio->respuesta_correcta ?? ''));
        }

        $esPerfecto = $esCorrecto && $registro->intento === 1;
        $registro->es_correcto = $esCorrecto;
        $registro->es_perfecto = $esPerfecto;
        $registro->completado_at = now();
        $registro->save();

        if (! $esCorrecto) {
            return response()->json([
                'success' => true,
                'data' => ['es_correcto' => false],
                'message' => '¡Casi! Inténtalo de nuevo 💪',
            ]);
        }

        // Recompensa especial del desafío (adicional a la del ejercicio)
        $gamificacion = $this->gamificacion->procesarEjercicioCompletado($user, $ejercicio, $esPerfecto);
        $registro->recompensa_ganada = $desafio->recompensa + collect($gamificacion['recompensas'])->sum('monto');
        $registro->save();

        return response()->json([
            'success' => true,
            'data' => ['es_correcto' => true, 'es_perfecto' => $esPerfecto],
            'message' => '¡Desafío completado! ⭐',
            'meta' => [
                'recompensa_ganada' => $registro->recompensa_ganada,
                'nuevos_logros' => $gamificacion['nuevos_logros'],
                'racha_actual' => $gamificacion['racha_actual'],
                'billetera_total' => $gamificacion['billetera_total'],
            ],
        ]);
    }
}
