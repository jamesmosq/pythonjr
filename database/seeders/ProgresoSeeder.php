<?php

namespace Database\Seeders;

use App\Models\Modulo;
use App\Models\ProgresoModulo;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProgresoSeeder extends Seeder
{
    public function run(): void
    {
        $estudiante = User::where('email', 'santiago@pythonjr.com')->first();
        $modulos = Modulo::orderBy('nivel')->orderBy('orden')->get();

        foreach ($modulos as $index => $modulo) {
            ProgresoModulo::create([
                'user_id' => $estudiante->id,
                'modulo_id' => $modulo->id,
                // Solo el primer módulo está disponible, el resto bloqueados
                'estado' => $index === 0 ? 'disponible' : 'bloqueado',
            ]);
        }
    }
}
