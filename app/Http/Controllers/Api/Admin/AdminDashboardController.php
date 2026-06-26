<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\EjercicioCompletado;
use App\Models\Modulo;
use App\Models\ProgresoModulo;
use App\Models\TransaccionBilletera;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    public function resetPasswordEstudiante(User $estudiante, Request $request): JsonResponse
    {
        if ($estudiante->parent_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'No autorizado.'], 403);
        }

        $nueva = Str::random(4) . rand(10, 99) . Str::random(2);
        $estudiante->password = Hash::make($nueva);
        $estudiante->save();
        $estudiante->tokens()->delete();

        return response()->json([
            'success' => true,
            'data'    => ['nueva_contrasena' => $nueva],
        ]);
    }

    public function estudiantes(Request $request): JsonResponse
    {
        $query = User::where('role', 'estudiante')->orderBy('name');

        if ($request->user()->role === 'admin') {
            $query->where('parent_id', $request->user()->id);
        }

        $lista = $query->get()->map(fn ($u) => [
            'id'     => $u->id,
            'nombre' => $u->name,
            'email'  => $u->email,
            'avatar' => $u->avatar,
        ]);

        return response()->json(['success' => true, 'data' => $lista]);
    }

    public function index(Request $request): JsonResponse
    {
        $estudianteId = $request->query('estudiante_id');

        $query = User::where('role', 'estudiante');
        if ($request->user()->role === 'admin') {
            $query->where('parent_id', $request->user()->id);
        }

        $estudiante = $estudianteId
            ? $query->find($estudianteId)
            : $query->first();

        if (! $estudiante) {
            return response()->json(['success' => true, 'data' => null]);
        }

        $billetera = $estudiante->billetera;
        $racha     = $estudiante->racha;

        // ── Progreso por módulo ──────────────────────────────────────────────
        $modulos   = Modulo::orderBy('nivel')->orderBy('orden')->get();
        $progresos = ProgresoModulo::where('user_id', $estudiante->id)->get()->keyBy('modulo_id');

        $completadosIds = EjercicioCompletado::where('user_id', $estudiante->id)
            ->where('es_correcto', true)
            ->pluck('ejercicio_id')
            ->toArray();

        $progresoModulos = $modulos->map(function ($mod) use ($progresos, $completadosIds) {
            $progreso  = $progresos->get($mod->id);
            $ejIds     = $mod->ejercicios()->pluck('id')->toArray();
            $obligIds  = $mod->ejercicios()->where('es_obligatorio', true)->pluck('id')->toArray();

            $completados        = count(array_intersect($ejIds, $completadosIds));
            $obligCompletados   = count(array_intersect($obligIds, $completadosIds));

            return [
                'titulo'             => $mod->titulo,
                'icono'              => $mod->icono,
                'nivel'              => $mod->nivel,
                'slug'               => $mod->slug,
                'estado'             => $progreso?->estado ?? 'bloqueado',
                'total_ejercicios'   => count($ejIds),
                'completados'        => $completados,
                'obligatorios_total' => count($obligIds),
                'obligatorios_ok'    => $obligCompletados,
                'porcentaje'         => count($ejIds) > 0 ? round($completados / count($ejIds) * 100) : 0,
                'recompensa_base'    => $mod->recompensa_base,
                'iniciado_at'        => $progreso?->iniciado_at,
                'completado_at'      => $progreso?->completado_at,
            ];
        });

        // ── Estadísticas globales ────────────────────────────────────────────
        $totalCompletados = EjercicioCompletado::where('user_id', $estudiante->id)->where('es_correcto', true)->count();
        $totalPerfectos   = EjercicioCompletado::where('user_id', $estudiante->id)->where('es_perfecto', true)->count();

        $pendientesVal = EjercicioCompletado::where('user_id', $estudiante->id)
            ->where('validado_por_padre', false)
            ->where('validado_por_ia', false)
            ->where('es_correcto', false)
            ->whereHas('ejercicio', fn ($q) => $q->whereIn('tipo', ['codigo_libre', 'mini_proyecto']))
            ->count();

        $inicioMes = now()->startOfMonth();

        $diasActivosMes = EjercicioCompletado::where('user_id', $estudiante->id)
            ->where('completado_at', '>=', $inicioMes)
            ->where('es_correcto', true)
            ->selectRaw("DATE(completado_at) as dia")
            ->distinct()
            ->count();

        $diasTotalesActivos = EjercicioCompletado::where('user_id', $estudiante->id)
            ->where('es_correcto', true)
            ->selectRaw("DATE(completado_at) as dia")
            ->distinct()
            ->count();

        $promedioDia = $diasTotalesActivos > 0
            ? round($totalCompletados / $diasTotalesActivos, 1)
            : 0;

        $porcentajePerfecto = $totalCompletados > 0
            ? round($totalPerfectos / $totalCompletados * 100)
            : 0;

        $primerActividad = EjercicioCompletado::where('user_id', $estudiante->id)
            ->where('es_correcto', true)
            ->min('completado_at');

        // ── Actividad semanal — últimos 7 días completos ─────────────────────
        $actividadSemanal = EjercicioCompletado::where('user_id', $estudiante->id)
            ->where('completado_at', '>=', now()->subDays(6)->startOfDay())
            ->where('es_correcto', true)
            ->selectRaw("DATE(completado_at) as fecha, COUNT(*) as ejercicios, SUM(CASE WHEN es_perfecto THEN 1 ELSE 0 END) as perfectos")
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get()
            ->keyBy('fecha');

        $semanalCompleto = collect();
        for ($i = 6; $i >= 0; $i--) {
            $fecha = now()->subDays($i)->format('Y-m-d');
            $dia   = $actividadSemanal->get($fecha);
            $semanalCompleto->push([
                'fecha'      => $fecha,
                'dia'        => Carbon::parse($fecha)->locale('es')->isoFormat('ddd'),
                'ejercicios' => (int) ($dia?->ejercicios ?? 0),
                'perfectos'  => (int) ($dia?->perfectos ?? 0),
            ]);
        }

        // ── Actividad mensual — últimos 30 días ──────────────────────────────
        $mensual = EjercicioCompletado::where('user_id', $estudiante->id)
            ->where('completado_at', '>=', now()->subDays(29)->startOfDay())
            ->where('es_correcto', true)
            ->selectRaw("DATE(completado_at) as fecha, COUNT(*) as ejercicios")
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get()
            ->map(fn ($r) => ['fecha' => $r->fecha, 'ejercicios' => (int) $r->ejercicios]);

        // ── Actividad reciente ───────────────────────────────────────────────
        $actividadReciente = EjercicioCompletado::where('user_id', $estudiante->id)
            ->with('ejercicio.modulo')
            ->orderByDesc('completado_at')
            ->limit(10)
            ->get()
            ->map(fn ($ec) => [
                'ejercicio_titulo' => $ec->ejercicio?->titulo ?? '—',
                'modulo_titulo'    => $ec->ejercicio?->modulo?->titulo ?? '—',
                'tipo'             => $ec->ejercicio?->tipo,
                'es_correcto'      => $ec->es_correcto,
                'es_perfecto'      => $ec->es_perfecto,
                'validado_por_ia'  => $ec->validado_por_ia,
                'recompensa'       => $ec->recompensa_ganada ?? 0,
                'completado_at'    => $ec->completado_at,
                'intento'          => $ec->intento,
            ]);

        // ── Historial de ingresos ────────────────────────────────────────────
        $ingresos = TransaccionBilletera::where('user_id', $estudiante->id)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->map(fn ($t) => [
                'tipo'        => $t->tipo,
                'descripcion' => $t->descripcion,
                'monto'       => $t->monto,
                'created_at'  => $t->created_at,
            ]);

        $ingresosEstaSemana = TransaccionBilletera::where('user_id', $estudiante->id)
            ->where('monto', '>', 0)
            ->where('created_at', '>=', now()->startOfWeek())
            ->sum('monto');

        $ingresosEsteMes = TransaccionBilletera::where('user_id', $estudiante->id)
            ->where('monto', '>', 0)
            ->where('created_at', '>=', $inicioMes)
            ->sum('monto');

        return response()->json([
            'success' => true,
            'data'    => [
                'estudiante' => [
                    'id'     => $estudiante->id,
                    'nombre' => $estudiante->name,
                    'avatar' => $estudiante->avatar,
                ],
                'billetera' => [
                    'saldo_total'          => $billetera?->saldo_total ?? 0,
                    'saldo_pendiente'      => $billetera?->saldo_pendiente ?? 0,
                    'saldo_pagado'         => $billetera?->saldo_pagado ?? 0,
                    'ingresos_esta_semana' => (int) $ingresosEstaSemana,
                    'ingresos_este_mes'    => (int) $ingresosEsteMes,
                ],
                'racha' => [
                    'dias_actuales' => $racha?->dias_actuales ?? 0,
                    'dias_maximos'  => $racha?->dias_maximos ?? 0,
                ],
                'estadisticas' => [
                    'modulos_completados'   => $progresoModulos->where('estado', 'completado')->count(),
                    'modulos_en_progreso'   => $progresoModulos->whereIn('estado', ['en_progreso', 'disponible'])->count(),
                    'ejercicios_totales'    => $totalCompletados,
                    'ejercicios_perfectos'  => $totalPerfectos,
                    'pendientes_validacion' => $pendientesVal,
                    'porcentaje_perfecto'   => $porcentajePerfecto,
                    'dias_activos_mes'      => $diasActivosMes,
                    'promedio_por_dia'      => $promedioDia,
                    'primer_actividad'      => $primerActividad,
                ],
                'progreso_modulos'   => $progresoModulos->values(),
                'actividad_reciente' => $actividadReciente,
                'progreso_semanal'   => $semanalCompleto->values(),
                'progreso_mensual'   => $mensual->values(),
                'historial_ingresos' => $ingresos,
            ],
        ]);
    }
}
