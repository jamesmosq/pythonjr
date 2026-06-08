<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ModuloSeeder::class,
            LeccionSeeder::class,
            EjercicioSeeder::class,
            LogroSeeder::class,
            ProgresoSeeder::class,
        ]);
    }
}
