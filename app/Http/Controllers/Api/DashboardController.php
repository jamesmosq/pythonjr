<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DesafioDia;
use App\Models\EjercicioCompletado;
use App\Models\Modulo;
use App\Models\ProgresoModulo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $billetera = $user->billetera;
        $racha = $user->racha;

        // Módulo actual en progreso
        $progresoActual = ProgresoModulo::where('user_id', $user->id)
            ->where('estado', 'en_progreso')
            ->with('modulo')
            ->first();

        if (! $progresoActual) {
            $progresoActual = ProgresoModulo::where('user_id', $user->id)
                ->where('estado', 'disponible')
                ->with('modulo')
                ->first();
        }

        // Ejercicios completados hoy
        $ejerciciosHoy = EjercicioCompletado::where('user_id', $user->id)
            ->whereDate('completado_at', now()->toDateString())
            ->count();

        // Desafío del día
        $desafioHoy = DesafioDia::where('fecha', now()->toDateString())
            ->with('ejercicio')
            ->first();

        $desafioCompletado = $desafioHoy
            ? EjercicioCompletado::where('user_id', $user->id)
                ->where('ejercicio_id', $desafioHoy->ejercicio_id)
                ->where('es_correcto', true)
                ->exists()
            : false;

        // Resumen de módulos
        $progresos = ProgresoModulo::where('user_id', $user->id)
            ->with('modulo')
            ->get()
            ->map(fn ($p) => [
                'modulo_slug' => $p->modulo->slug,
                'modulo_titulo' => $p->modulo->titulo,
                'modulo_icono' => $p->modulo->icono,
                'estado' => $p->estado,
                'nivel' => $p->modulo->nivel,
            ]);

        return response()->json([
            'success' => true,
            'data' => [
                'usuario' => [
                    'nombre' => $user->name,
                    'avatar' => $user->avatar,
                ],
                'billetera' => [
                    'saldo_total' => $billetera?->saldo_total ?? 0,
                    'saldo_pendiente' => $billetera?->saldo_pendiente ?? 0,
                ],
                'racha' => [
                    'dias_actuales' => $racha?->dias_actuales ?? 0,
                    'dias_maximos' => $racha?->dias_maximos ?? 0,
                    'activa_hoy' => $racha?->ultima_actividad_at?->toDateString() === now()->toDateString(),
                ],
                'modulo_actual' => $progresoActual ? [
                    'slug' => $progresoActual->modulo->slug,
                    'titulo' => $progresoActual->modulo->titulo,
                    'icono' => $progresoActual->modulo->icono,
                    'estado' => $progresoActual->estado,
                ] : null,
                'desafio_hoy' => $desafioHoy ? [
                    'ejercicio_id' => $desafioHoy->ejercicio_id,
                    'titulo' => $desafioHoy->ejercicio->titulo,
                    'recompensa' => $desafioHoy->recompensa,
                    'completado' => $desafioCompletado,
                    'caduca_at' => now()->endOfDay()->toISOString(),
                ] : null,
                'ejercicios_hoy' => $ejerciciosHoy,
                'modulos' => $progresos,
            ],
        ]);
    }
}
