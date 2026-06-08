<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IntentarEjercicioRequest;
use App\Http\Resources\EjercicioResource;
use App\Models\Ejercicio;
use App\Models\EjercicioCompletado;
use App\Services\GamificacionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EjercicioController extends Controller
{
    public function __construct(private GamificacionService $gamificacion) {}

    public function show(Request $request, Ejercicio $ejercicio): JsonResponse
    {
        $ejercicio->load(['opciones', 'completados', 'modulo:id,slug,titulo']);

        return response()->json([
            'success' => true,
            'data' => new EjercicioResource($ejercicio),
        ]);
    }

    public function intentar(IntentarEjercicioRequest $request, Ejercicio $ejercicio): JsonResponse
    {
        $user = $request->user();
        $respuesta = trim((string) $request->input('respuesta', ''));

        // Un ejercicio solo puede completarse una vez
        $yaCompletado = EjercicioCompletado::where('user_id', $user->id)
            ->where('ejercicio_id', $ejercicio->id)
            ->where('es_correcto', true)
            ->exists();

        if ($yaCompletado) {
            return response()->json([
                'success' => false,
                'message' => 'Este ejercicio ya fue completado.',
            ], 422);
        }

        $registro = EjercicioCompletado::firstOrNew([
            'user_id' => $user->id,
            'ejercicio_id' => $ejercicio->id,
        ]);

        $registro->modulo_id = $ejercicio->modulo_id;
        $registro->respuesta_dada = $respuesta;
        $registro->intento = ($registro->intento ?? 0) + 1;

        if ($ejercicio->esAutoVerificable()) {
            [$esCorrecto, $esPerfecto] = $this->verificarRespuesta($ejercicio, $respuesta, $registro->intento);
            $registro->es_correcto = $esCorrecto;
            $registro->es_perfecto = $esPerfecto;
            $registro->completado_at = now();
            $registro->save();

            if (! $esCorrecto) {
                return response()->json([
                    'success' => true,
                    'data' => ['es_correcto' => false, 'intento' => $registro->intento],
                    'message' => '¡Casi! Inténtalo de nuevo 💪',
                    'meta' => ['pista_disponible' => $registro->intento >= 2],
                ]);
            }

            $gamificacion = $this->gamificacion->procesarEjercicioCompletado($user, $ejercicio, $esPerfecto);
            $registro->recompensa_ganada = collect($gamificacion['recompensas'])->sum('monto');
            $registro->save();

            return response()->json([
                'success' => true,
                'data' => [
                    'es_correcto' => true,
                    'es_perfecto' => $esPerfecto,
                    'intento' => $registro->intento,
                ],
                'message' => $esPerfecto ? '¡PERFECTO! ⭐ ¡Al primer intento!' : '¡Correcto! 🎉',
                'meta' => [
                    'recompensa_ganada' => collect($gamificacion['recompensas'])->sum('monto'),
                    'recompensas' => $gamificacion['recompensas'],
                    'nuevos_logros' => $gamificacion['nuevos_logros'],
                    'racha_actual' => $gamificacion['racha_actual'],
                    'billetera_total' => $gamificacion['billetera_total'],
                ],
            ]);
        }

        // Ejercicios que requieren validación del padre (codigo_libre, mini_proyecto)
        $registro->es_correcto = false;
        $registro->es_perfecto = false;
        $registro->validado_por_padre = false;
        $registro->completado_at = now();
        $registro->save();

        return response()->json([
            'success' => true,
            'data' => ['pendiente_validacion' => true],
            'message' => '¡Listo! Le avisamos a papá para que revise tu código 👨‍💻',
        ]);
    }

    private function verificarRespuesta(Ejercicio $ejercicio, string $respuesta, int $intento): array
    {
        if ($ejercicio->tipo === 'quiz_opcion') {
            $opcionCorrecta = $ejercicio->opciones->firstWhere('es_correcta', true);
            $esCorrecto = $opcionCorrecta && (string) $opcionCorrecta->id === $respuesta;
        } else {
            // quiz_texto — normalizar y comparar
            $esCorrecto = mb_strtolower(trim($respuesta)) === mb_strtolower(trim($ejercicio->respuesta_correcta ?? ''));
        }

        $esPerfecto = $esCorrecto && $intento === 1;

        return [$esCorrecto, $esPerfecto];
    }
}
