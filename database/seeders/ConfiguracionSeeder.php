<?php

namespace Database\Seeders;

use App\Models\ConfiguracionPlataforma;
use Illuminate\Database\Seeder;

class ConfiguracionSeeder extends Seeder
{
    public function run(): void
    {
        $config = [
            ['clave' => 'bono_racha_3d',              'valor' => '8000',  'tipo' => 'number',  'descripcion' => 'Bono por racha de 3 días seguidos (COP)'],
            ['clave' => 'bono_racha_7d',              'valor' => '20000', 'tipo' => 'number',  'descripcion' => 'Bono por racha de 7 días seguidos (COP)'],
            ['clave' => 'multiplicador_finde_activo', 'valor' => 'false', 'tipo' => 'boolean', 'descripcion' => 'Activar multiplicador de fin de semana'],
            ['clave' => 'multiplicador_finde_factor', 'valor' => '2',     'tipo' => 'float',   'descripcion' => 'Factor multiplicador de fin de semana (ej: 2 = doble)'],
            ['clave' => 'meta_semanal_activa',        'valor' => 'false', 'tipo' => 'boolean', 'descripcion' => 'Activar meta semanal de ejercicios'],
            ['clave' => 'meta_semanal_ejercicios',    'valor' => '5',     'tipo' => 'number',  'descripcion' => 'Número de ejercicios para completar la meta semanal'],
            ['clave' => 'meta_semanal_recompensa',    'valor' => '10000', 'tipo' => 'number',  'descripcion' => 'Recompensa al cumplir la meta semanal (COP)'],
        ];

        foreach ($config as $item) {
            ConfiguracionPlataforma::firstOrCreate(['clave' => $item['clave']], $item);
        }
    }
}
