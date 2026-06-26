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
            Modulo2Seeder::class,
            Modulo3Seeder::class,
            Modulo4Seeder::class,
            Modulo5Seeder::class,
            Modulo6Seeder::class,
            Modulo7Seeder::class,
            Modulo8Seeder::class,
            Modulo9Seeder::class,
            ModuloBDSeeder::class,
            ModuloHTMLSeeder::class,
            ModuloCSSSeeder::class,
            LogroSeeder::class,
            ProgresoSeeder::class,
            ConfiguracionSeeder::class,
        ]);
    }
}
