<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\EjercicioCompletado;
use App\Models\ProgresoModulo;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $estudiante = User::where('role', 'estudiante')->first();

        if (! $estudiante) {
            return response()->json(['success' => true, 'data' => null]);
        }

        $billetera = $estudiante->billetera;
        $racha = $estudiante->racha;

        $progresos = ProgresoModulo::where('user_id', $estudiante->id)
            ->with('modulo')
            ->get();

        $modulosCompletados = $progresos->where('estado', 'completado')->count();
        $modulosEnProgreso = $progresos->where('estado', 'en_progreso')->count();

        $ejerciciosTotales = EjercicioCompletado::where('user_id', $estudiante->id)->count();
        $ejerciciosPerfectos = EjercicioCompletado::where('user_id', $estudiante->id)->where('es_perfecto', true)->count();
        $pendientesValidacion = EjercicioCompletado::where('user_id', $estudiante->id)
            ->where('validado_por_padre', false)
            ->where('es_correcto', false)
            ->whereHas('ejercicio', fn ($q) => $q->whereIn('tipo', ['codigo_libre', 'mini_proyecto']))
            ->count();

        // Progreso semanal (últimos 7 días)
        $semanal = EjercicioCompletado::where('user_id', $estudiante->id)
            ->where('completado_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw("DATE(completado_at) as fecha, COUNT(*) as ejercicios")
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get()
            ->map(fn ($r) => ['fecha' => $r->fecha, 'ejercicios' => $r->ejercicios]);

        return response()->json([
            'success' => true,
            'data' => [
                'estudiante' => [
                    'nombre' => $estudiante->name,
                    'avatar' => $estudiante->avatar,
                ],
                'billetera' => [
                    'saldo_total' => $billetera?->saldo_total ?? 0,
                    'saldo_pendiente' => $billetera?->saldo_pendiente ?? 0,
                    'saldo_pagado' => $billetera?->saldo_pagado ?? 0,
                ],
                'racha' => [
                    'dias_actuales' => $racha?->dias_actuales ?? 0,
                    'dias_maximos' => $racha?->dias_maximos ?? 0,
                ],
                'estadisticas' => [
                    'modulos_completados' => $modulosCompletados,
                    'modulos_en_progreso' => $modulosEnProgreso,
                    'ejercicios_totales' => $ejerciciosTotales,
                    'ejercicios_perfectos' => $ejerciciosPerfectos,
                    'pendientes_validacion' => $pendientesValidacion,
                ],
                'progreso_semanal' => $semanal,
            ],
        ]);
    }
}
