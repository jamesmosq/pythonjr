<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\EjercicioCompletado;
use App\Services\BilleteraService;
use App\Services\GamificacionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ValidacionController extends Controller
{
    public function __construct(
        private GamificacionService $gamificacion,
        private BilleteraService $billeteraService,
    ) {}

    public function pendientes(Request $request): JsonResponse
    {
        $pendientes = EjercicioCompletado::where('validado_por_padre', false)
            ->where('es_correcto', false)
            ->whereHas('ejercicio', fn ($q) => $q->whereIn('tipo', ['codigo_libre', 'mini_proyecto']))
            ->with(['user', 'ejercicio.modulo'])
            ->orderBy('completado_at')
            ->get()
            ->map(fn ($ec) => [
                'id' => $ec->id,
                'estudiante' => $ec->user->name,
                'ejercicio_titulo' => $ec->ejercicio->titulo,
                'modulo_titulo' => $ec->ejercicio->modulo->titulo,
                'tipo' => $ec->ejercicio->tipo,
                'respuesta_dada' => $ec->respuesta_dada,
                'completado_at' => $ec->completado_at->toISOString(),
                'recompensa' => $ec->ejercicio->recompensa_ejercicio,
            ]);

        return response()->json(['success' => true, 'data' => $pendientes]);
    }

    public function validar(Request $request, EjercicioCompletado $completado): JsonResponse
    {
        $request->validate([
            'aprobar' => 'required|boolean',
            'feedback' => 'nullable|string|max:500',
        ]);

        if ($completado->validado_por_padre) {
            return response()->json(['success' => false, 'message' => 'Ya fue validado.'], 422);
        }

        $completado->validado_por_padre = true;

        if ($request->boolean('aprobar')) {
            $completado->es_correcto = true;
            // Primer intento de código libre siempre puede ser perfecto si el padre así lo decide
            $completado->es_perfecto = $completado->intento === 1;
            $completado->save();

            $gamificacion = $this->gamificacion->procesarEjercicioCompletado(
                $completado->user,
                $completado->ejercicio,
                $completado->es_perfecto
            );

            $completado->recompensa_ganada = collect($gamificacion['recompensas'])->sum('monto');
            $completado->save();

            return response()->json([
                'success' => true,
                'message' => '✅ Ejercicio aprobado. ¡Santiago ganó $' . number_format($completado->recompensa_ganada, 0, ',', '.') . '!',
                'meta' => [
                    'recompensa_ganada' => $completado->recompensa_ganada,
                    'nuevos_logros' => $gamificacion['nuevos_logros'],
                    'billetera_total' => $gamificacion['billetera_total'],
                ],
            ]);
        }

        // Rechazado — pedir corrección (se puede reintentar)
        $completado->es_correcto = false;
        $completado->save();

        return response()->json([
            'success' => true,
            'message' => '🔄 Se le pidió corrección al estudiante.',
        ]);
    }

    public function pagar(Request $request): JsonResponse
    {
        $request->validate([
            'monto' => 'required|integer|min:1000',
        ]);

        $estudiante = \App\Models\User::where('role', 'estudiante')->firstOrFail();

        $this->billeteraService->registrarPago($estudiante, $request->integer('monto'));

        $billetera = $estudiante->fresh()->billetera;

        return response()->json([
            'success' => true,
            'message' => '💸 Pago de $' . number_format($request->integer('monto'), 0, ',', '.') . ' registrado.',
            'data' => [
                'saldo_pendiente' => $billetera->saldo_pendiente,
                'saldo_pagado' => $billetera->saldo_pagado,
            ],
        ]);
    }
}
