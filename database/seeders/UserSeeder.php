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
        // Superadmin — dueño de la plataforma
        User::create([
            'name'      => 'James',
            'email'     => 'jamesmosqr@gmail.com',
            'password'  => Hash::make('superadmin123'),
            'role'      => 'superadmin',
            'avatar'    => '🦸',
            'parent_id' => null,
        ]);

        // Admin — primer papá de prueba
        $admin = User::create([
            'name'      => 'Papá',
            'email'     => 'admin@pythonjr.com',
            'password'  => Hash::make('admin123'),
            'role'      => 'admin',
            'avatar'    => '👨‍💻',
            'parent_id' => null,
        ]);

        $estudiantes = [
            [
                'name'      => 'Santiago',
                'email'     => 'santiago@pythonjr.com',
                'password'  => Hash::make('python123'),
                'role'      => 'estudiante',
                'avatar'    => '🧑‍🎓',
                'parent_id' => $admin->id,
            ],
            [
                'name'      => 'Nikolas',
                'email'     => 'nikolasmosqr@gmail.com',
                'password'  => Hash::make('python123'),
                'role'      => 'estudiante',
                'avatar'    => '🧑‍🎓',
                'parent_id' => $admin->id,
            ],
        ];

        foreach ($estudiantes as $datos) {
            $est = User::create($datos);

            Billetera::create([
                'user_id' => $est->id,
                'saldo_total' => 0,
                'saldo_pendiente' => 0,
                'saldo_pagado' => 0,
            ]);

            Racha::create([
                'user_id' => $est->id,
                'dias_actuales' => 0,
                'dias_maximos' => 0,
                'ultima_actividad_at' => null,
            ]);
        }
    }
}
