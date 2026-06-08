<?php

namespace App\Services;

use App\Models\Racha;
use App\Models\User;

class RachaService
{
    public function registrarActividad(User $user): array
    {
        $racha = $user->racha ?? Racha::create(['user_id' => $user->id]);

        $hoy = now()->toDateString();

        if ($racha->ultima_actividad_at?->toDateString() === $hoy) {
            return ['bono' => 0, 'tipo' => null];
        }

        $ayer = now()->subDay()->toDateString();

        if ($racha->ultima_actividad_at?->toDateString() === $ayer) {
            $racha->dias_actuales++;
        } else {
            // Racha rota — reiniciar sin penalización monetaria
            $racha->dias_actuales = 1;
            $racha->racha_3d_cobrada = false;
            $racha->racha_7d_cobrada = false;
        }

        $racha->ultima_actividad_at = $hoy;
        $racha->dias_maximos = max($racha->dias_maximos, $racha->dias_actuales);
        $racha->updated_at = now();
        $racha->save();

        if ($racha->dias_actuales >= 7 && ! $racha->racha_7d_cobrada) {
            $racha->racha_7d_cobrada = true;
            $racha->save();
            return ['bono' => 20000, 'tipo' => 'racha_7d'];
        }

        if ($racha->dias_actuales >= 3 && ! $racha->racha_3d_cobrada) {
            $racha->racha_3d_cobrada = true;
            $racha->save();
            return ['bono' => 8000, 'tipo' => 'racha_3d'];
        }

        return ['bono' => 0, 'tipo' => null];
    }
}
