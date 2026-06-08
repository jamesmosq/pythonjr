<?php

namespace App\Services;

use App\Models\Ejercicio;
use App\Models\EjercicioCompletado;
use App\Models\Logro;
use App\Models\LogroUsuario;
use App\Models\Modulo;
use App\Models\ProgresoModulo;
use App\Models\User;
use Illuminate\Support\Carbon;

class GamificacionService
{
    public function __construct(
        private BilleteraService $billeteraService,
        private RachaService $rachaService,
    ) {}

    public function procesarEjercicioCompletado(User $user, Ejercicio $ejercicio, bool $esPerfecto): array
    {
        $recompensas = [];

        // 1. Recompensa base del ejercicio
        $monto = $esPerfecto ? $ejercicio->recompensa_perfecto : $ejercicio->recompensa_ejercicio;
        $this->billeteraService->acreditar($user, $monto, $esPerfecto ? 'perfecto' : 'ejercicio', $ejercicio->id, 'ejercicio');
        $recompensas[] = ['tipo' => $esPerfecto ? 'perfecto' : 'ejercicio', 'monto' => $monto];

        // 2. Actualizar racha
        $rachaInfo = $this->rachaService->registrarActividad($user);
        if ($rachaInfo['bono'] > 0) {
            $this->billeteraService->acreditar($user, $rachaInfo['bono'], $rachaInfo['tipo']);
            $recompensas[] = ['tipo' => $rachaInfo['tipo'], 'monto' => $rachaInfo['bono']];
        }

        // 3. ¿Completó el módulo?
        $nuevosLogros = [];
        if ($this->moduloCompleto($user, $ejercicio->modulo)) {
            $modulo = $ejercicio->modulo;

            $this->actualizarProgresoModulo($user, $modulo);

            $this->billeteraService->acreditar($user, $modulo->recompensa_base, 'modulo_base', $modulo->id, 'modulo');
            $recompensas[] = ['tipo' => 'modulo_base', 'monto' => $modulo->recompensa_base];

            // 3a. ¿Bono de velocidad de módulo?
            if ($this->enTiempoRapido($user, $modulo)) {
                $this->billeteraService->acreditar($user, 10000, 'velocidad_modulo', $modulo->id, 'modulo');
                $recompensas[] = ['tipo' => 'velocidad_modulo', 'monto' => 10000];
            }

            // 3b. ¿Completó el nivel completo rápido?
            if ($this->nivelCompletoRapido($user, $modulo->nivel)) {
                $this->billeteraService->acreditar($user, 25000, 'velocidad_nivel');
                $recompensas[] = ['tipo' => 'velocidad_nivel', 'monto' => 25000];
            }

            // 3c. Desbloquear siguiente módulo
            $this->desbloquearSiguienteModulo($user, $modulo);
        }

        // 4. Verificar logros nuevos
        $nuevosLogros = $this->verificarLogros($user);

        $user->load('billetera');

        return [
            'recompensas' => $recompensas,
            'nuevos_logros' => $nuevosLogros,
            'racha_actual' => $user->racha?->dias_actuales ?? 0,
            'billetera_total' => $user->billetera?->saldo_total ?? 0,
        ];
    }

    private function moduloCompleto(User $user, Modulo $modulo): bool
    {
        $obligatorios = $modulo->ejercicios()->where('es_obligatorio', true)->pluck('id');

        if ($obligatorios->isEmpty()) {
            return false;
        }

        $completados = EjercicioCompletado::where('user_id', $user->id)
            ->whereIn('ejercicio_id', $obligatorios)
            ->where('es_correcto', true)
            ->count();

        return $completados >= $obligatorios->count();
    }

    private function actualizarProgresoModulo(User $user, Modulo $modulo): void
    {
        $progreso = ProgresoModulo::firstOrCreate(
            ['user_id' => $user->id, 'modulo_id' => $modulo->id],
            ['estado' => 'en_progreso']
        );

        if ($progreso->estado !== 'completado') {
            $progreso->estado = 'completado';
            $progreso->completado_at = now();
            $progreso->save();
        }
    }

    private function enTiempoRapido(User $user, Modulo $modulo): bool
    {
        $progreso = ProgresoModulo::where('user_id', $user->id)
            ->where('modulo_id', $modulo->id)
            ->first();

        if (! $progreso?->iniciado_at || $progreso->bono_velocidad_aplicado) {
            return false;
        }

        $diasTranscurridos = $progreso->iniciado_at->diffInDays(now());

        if ($diasTranscurridos <= 5) {
            $progreso->bono_velocidad_aplicado = true;
            $progreso->save();
            return true;
        }

        return false;
    }

    private function nivelCompletoRapido(User $user, int $nivel): bool
    {
        $modulos = Modulo::where('nivel', $nivel)->pluck('id');

        $progresos = ProgresoModulo::where('user_id', $user->id)
            ->whereIn('modulo_id', $modulos)
            ->where('estado', 'completado')
            ->get();

        if ($progresos->count() < $modulos->count()) {
            return false;
        }

        $primerInicio = $progresos->min('iniciado_at');
        if (! $primerInicio) {
            return false;
        }

        return Carbon::parse($primerInicio)->diffInDays(now()) <= 14;
    }

    private function desbloquearSiguienteModulo(User $user, Modulo $moduloActual): void
    {
        $siguiente = Modulo::where('nivel', $moduloActual->nivel)
            ->where('orden', $moduloActual->orden + 1)
            ->first();

        if (! $siguiente) {
            // Fin del nivel, buscar primer módulo del siguiente nivel
            $siguiente = Modulo::where('nivel', $moduloActual->nivel + 1)
                ->orderBy('orden')
                ->first();
        }

        if ($siguiente) {
            ProgresoModulo::where('user_id', $user->id)
                ->where('modulo_id', $siguiente->id)
                ->where('estado', 'bloqueado')
                ->update(['estado' => 'disponible']);
        }
    }

    private function verificarLogros(User $user): array
    {
        $user->load('ejerciciosCompletados', 'racha', 'progresoModulos', 'logros');
        $logrosYaTiene = $user->logros->pluck('logro_id')->toArray();

        $nuevos = [];

        $checks = [
            'primer-paso' => fn () => $user->ejerciciosCompletados->where('es_correcto', true)->count() >= 1,
            'tiro-perfecto' => fn () => $user->ejerciciosCompletados->where('es_perfecto', true)->count() >= 1,
            'francotirador' => fn () => $user->ejerciciosCompletados->where('es_perfecto', true)->count() >= 5,
            'ojo-de-aguila' => fn () => $user->ejerciciosCompletados->where('es_perfecto', true)->count() >= 15,
            'racha-3' => fn () => ($user->racha?->dias_actuales ?? 0) >= 3,
            'semana-completa' => fn () => ($user->racha?->dias_actuales ?? 0) >= 7,
            'imparable' => fn () => ($user->racha?->dias_actuales ?? 0) >= 14,
            'bienvenido-nivel1' => fn () => $this->nivelCompleto($user, 1),
            'explorer' => fn () => $this->nivelCompleto($user, 1),
            'constructor' => fn () => $this->nivelCompleto($user, 2),
            'arquitecto' => fn () => $this->nivelCompleto($user, 3),
            'master' => fn () => $this->nivelCompleto($user, 4),
        ];

        foreach ($checks as $slug => $condicion) {
            $logro = Logro::where('slug', $slug)->first();
            if (! $logro || in_array($logro->id, $logrosYaTiene)) {
                continue;
            }

            if ($condicion()) {
                LogroUsuario::create([
                    'user_id' => $user->id,
                    'logro_id' => $logro->id,
                ]);
                $nuevos[] = [
                    'slug' => $logro->slug,
                    'nombre' => $logro->nombre,
                    'icono' => $logro->icono,
                ];
            }
        }

        return $nuevos;
    }

    private function nivelCompleto(User $user, int $nivel): bool
    {
        $totalModulos = Modulo::where('nivel', $nivel)->count();
        if ($totalModulos === 0) {
            return false;
        }

        $completados = ProgresoModulo::where('user_id', $user->id)
            ->whereHas('modulo', fn ($q) => $q->where('nivel', $nivel))
            ->where('estado', 'completado')
            ->count();

        return $completados >= $totalModulos;
    }
}
