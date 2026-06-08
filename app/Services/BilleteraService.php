<?php

namespace App\Services;

use App\Models\Billetera;
use App\Models\TransaccionBilletera;
use App\Models\User;

class BilleteraService
{
    public function acreditar(User $user, int $monto, string $tipo, ?int $referenciaId = null, ?string $referenciaTipo = null, ?string $descripcion = null): void
    {
        $billetera = $user->billetera ?? Billetera::create(['user_id' => $user->id]);

        $billetera->saldo_total += $monto;
        $billetera->saldo_pendiente += $monto;
        $billetera->updated_at = now();
        $billetera->save();

        TransaccionBilletera::create([
            'user_id' => $user->id,
            'tipo' => $tipo,
            'descripcion' => $descripcion ?? $this->descripcionPorTipo($tipo, $monto),
            'monto' => $monto,
            'referencia_id' => $referenciaId,
            'referencia_tipo' => $referenciaTipo,
        ]);
    }

    public function registrarPago(User $user, int $monto): void
    {
        $billetera = $user->billetera;

        $billetera->saldo_pendiente = max(0, $billetera->saldo_pendiente - $monto);
        $billetera->saldo_pagado += $monto;
        $billetera->updated_at = now();
        $billetera->save();

        TransaccionBilletera::create([
            'user_id' => $user->id,
            'tipo' => 'pago_padre',
            'descripcion' => "Pago registrado por el padre: $" . number_format($monto, 0, ',', '.'),
            'monto' => -$monto,
        ]);
    }

    private function descripcionPorTipo(string $tipo, int $monto): string
    {
        return match ($tipo) {
            'ejercicio' => 'Ejercicio completado',
            'perfecto' => '¡Ejercicio perfecto al primer intento!',
            'modulo_base' => 'Módulo completado',
            'racha_3d' => '🔥 ¡Racha de 3 días!',
            'racha_7d' => '🔥🔥 ¡Racha de 7 días!',
            'velocidad_modulo' => '⚡ Bono de velocidad de módulo',
            'velocidad_nivel' => '⚡ Bono de velocidad de nivel',
            'desafio_dia' => '⭐ Desafío del día',
            'bono_nivel'          => 'Bono de nivel completado',
            'bono_sorpresa'       => '🎁 ¡Bono sorpresa de papá!',
            'meta_semanal'        => '🎯 ¡Meta semanal cumplida!',
            'hackathon_base'      => '🏆 Hackathon completado',
            'hackathon_velocidad' => '⚡ Hackathon completado con bono velocidad',
            'hackathon_perfecto'  => '⭐ Hackathon perfecto',
            default               => 'Recompensa',
        };
    }
}
