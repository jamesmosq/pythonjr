<?php

namespace App\Services;

use App\Models\EjercicioCompletado;
use App\Models\Hackathon;
use App\Models\HackathonEjercicio;
use App\Models\HackathonParticipacion;
use App\Models\Modulo;
use App\Models\ProgresoModulo;
use App\Models\User;
use App\Models\Ejercicio;

class HackathonService
{
    public function __construct(
        private BilleteraService $billeteraService,
        private AnthropicGradingService $anthropic,
    ) {}

    public function generarEjercicios(User $user, Hackathon $hackathon): void
    {
        $cantidad = match ($hackathon->tipo) {
            'sprint'   => 5,
            'maraton'  => 10,
            'maestro'  => 8,
            'tematico' => 6,
            default    => 6,
        };

        $completadosIds = EjercicioCompletado::where('user_id', $user->id)
            ->where('es_correcto', true)
            ->pluck('ejercicio_id');

        $fallidosIds = EjercicioCompletado::where('user_id', $user->id)
            ->where('es_correcto', false)
            ->where('intento', '>=', 2)
            ->pluck('ejercicio_id');

        $modulosCompletosIds = ProgresoModulo::where('user_id', $user->id)
            ->where('estado', 'completado')
            ->pluck('modulo_id');

        $moduloActualId = ProgresoModulo::where('user_id', $user->id)
            ->whereIn('estado', ['disponible', 'en_progreso'])
            ->value('modulo_id');

        $seleccionados = collect();

        // Repaso: ejercicios ya completados (confianza)
        $repaso = Ejercicio::whereIn('modulo_id', $modulosCompletosIds)
            ->whereIn('id', $completadosIds)
            ->inRandomOrder()
            ->limit(2)
            ->get();
        $seleccionados = $seleccionados->merge($repaso);

        // Desafío: donde tuvo dificultad
        if ($fallidosIds->isNotEmpty()) {
            $desafios = Ejercicio::whereIn('id', $fallidosIds)
                ->inRandomOrder()
                ->limit(2)
                ->get();
            $seleccionados = $seleccionados->merge($desafios);
        }

        // Nivel actual: ejercicios no completados del módulo en progreso
        if ($moduloActualId) {
            $actuales = Ejercicio::where('modulo_id', $moduloActualId)
                ->whereNotIn('id', $completadosIds)
                ->inRandomOrder()
                ->limit(2)
                ->get();
            $seleccionados = $seleccionados->merge($actuales);
        }

        // Completar hasta la cantidad objetivo
        $faltantes = $cantidad - $seleccionados->unique('id')->count();
        if ($faltantes > 0) {
            $extra = Ejercicio::whereIn('modulo_id', $modulosCompletosIds)
                ->whereNotIn('id', $seleccionados->pluck('id'))
                ->inRandomOrder()
                ->limit($faltantes)
                ->get();
            $seleccionados = $seleccionados->merge($extra);
        }

        $seleccionados->unique('id')->values()->each(function ($ejercicio, $index) use ($hackathon) {
            HackathonEjercicio::create([
                'hackathon_id'  => $hackathon->id,
                'ejercicio_id'  => $ejercicio->id,
                'orden'         => $index + 1,
            ]);
        });
    }

    public function registrarEjercicioCompletado(User $user, Hackathon $hackathon, bool $esPerfecto): void
    {
        $participacion = HackathonParticipacion::firstOrCreate(
            ['hackathon_id' => $hackathon->id, 'user_id' => $user->id],
        );

        $participacion->ejercicios_completados++;
        if ($esPerfecto) {
            $participacion->ejercicios_perfectos++;
        }
        $participacion->save();

        // ¿Completó todos?
        $totalEjercicios = $hackathon->ejercicios()->count();
        if ($participacion->ejercicios_completados >= $totalEjercicios) {
            $this->finalizarHackathon($user, $hackathon, $participacion);
        }
    }

    private function finalizarHackathon(User $user, Hackathon $hackathon, HackathonParticipacion $participacion): void
    {
        $recompensa = $hackathon->recompensa_base;
        $tipo = 'hackathon_base';

        // Bono velocidad si terminó antes del límite
        if ($hackathon->ends_at && now()->isBefore($hackathon->ends_at)) {
            $recompensa = (int) round($recompensa * $hackathon->multiplicador_velocidad);
            $tipo = 'hackathon_velocidad';
        }

        // Bono perfección si todos los ejercicios fueron perfectos
        $total = $hackathon->ejercicios()->count();
        if ($participacion->ejercicios_perfectos >= $total) {
            $recompensa = (int) round($recompensa * $hackathon->multiplicador_perfecto);
            $tipo = 'hackathon_perfecto';
        }

        $this->billeteraService->acreditar(
            $user, $recompensa, $tipo,
            $hackathon->id, 'hackathon',
            "🏆 Hackathon completado: {$hackathon->nombre}"
        );

        $reporte = $this->anthropic->generarReporteHackathon(
            $user,
            $participacion->ejercicios_completados,
            $participacion->ejercicios_perfectos,
            $total,
            $hackathon->nombre
        );

        $participacion->recompensa_ganada = $recompensa;
        $participacion->reporte_ia = $reporte;
        $participacion->finalizado_at = now();
        $participacion->save();
    }

    public function hackathonActivoParaUsuario(User $user): ?Hackathon
    {
        return Hackathon::where('estado', 'activo')
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            })
            ->latest()
            ->first();
    }
}
