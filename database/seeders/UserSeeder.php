<?php

namespace Database\Seeders;

use App\Models\Billetera;
use App\Models\Racha;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Papá',
            'email' => 'admin@pythonjr.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'avatar' => '👨‍💻',
        ]);

        $estudiante = User::create([
            'name' => 'Santiago',
            'email' => 'santiago@pythonjr.com',
            'password' => Hash::make('python123'),
            'role' => 'estudiante',
            'avatar' => '🧑‍🎓',
        ]);

        Billetera::create([
            'user_id' => $estudiante->id,
            'saldo_total' => 0,
            'saldo_pendiente' => 0,
            'saldo_pagado' => 0,
        ]);

        Racha::create([
            'user_id' => $estudiante->id,
            'dias_actuales' => 0,
            'dias_maximos' => 0,
            'ultima_actividad_at' => null,
        ]);
    }
}
