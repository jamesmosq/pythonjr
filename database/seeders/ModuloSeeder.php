<?php

namespace Database\Seeders;

use App\Models\Modulo;
use Illuminate\Database\Seeder;

class ModuloSeeder extends Seeder
{
    public function run(): void
    {
        $modulos = [
            // Nivel 1 — Aprendiz
            [
                'nivel' => 1, 'orden' => 1, 'slug' => 'hola-python',
                'titulo' => '¡Hola, Python!',
                'descripcion' => 'Aprende las bases: variables, tipos de datos, print() e input(). Tu primer programa real.',
                'icono' => '🐍', 'dias_estimados' => 7, 'recompensa_base' => 15000, 'activo' => true,
            ],
            [
                'nivel' => 1, 'orden' => 2, 'slug' => 'tomando-decisiones',
                'titulo' => 'Tomando decisiones',
                'descripcion' => 'if, elif, else y operadores lógicos. Haz que tu programa piense.',
                'icono' => '🤔', 'dias_estimados' => 7, 'recompensa_base' => 15000, 'activo' => true,
            ],
            [
                'nivel' => 1, 'orden' => 3, 'slug' => 'repitiendo-cosas',
                'titulo' => 'Repitiendo cosas',
                'descripcion' => 'for, while, range(), break y continue. Haz que el computador repita trabajo por ti.',
                'icono' => '🔁', 'dias_estimados' => 7, 'recompensa_base' => 15000, 'activo' => true,
            ],
            // Nivel 2 — Explorador
            [
                'nivel' => 2, 'orden' => 1, 'slug' => 'funciones-magicas',
                'titulo' => 'Funciones mágicas',
                'descripcion' => 'def, parámetros y return. Organiza tu código como un pro.',
                'icono' => '✨', 'dias_estimados' => 7, 'recompensa_base' => 25000, 'activo' => true,
            ],
            [
                'nivel' => 2, 'orden' => 2, 'slug' => 'colecciones-de-cosas',
                'titulo' => 'Colecciones de cosas',
                'descripcion' => 'Listas, diccionarios y tuplas. Guarda y organiza montones de datos.',
                'icono' => '📦', 'dias_estimados' => 7, 'recompensa_base' => 25000, 'activo' => true,
            ],
            [
                'nivel' => 2, 'orden' => 3, 'slug' => 'guardando-informacion',
                'titulo' => 'Guardando información',
                'descripcion' => 'Archivos .txt y .json, try/except. Haz programas que recuerdan cosas.',
                'icono' => '💾', 'dias_estimados' => 7, 'recompensa_base' => 25000, 'activo' => true,
            ],
            // Nivel 3 — Constructor
            [
                'nivel' => 3, 'orden' => 1, 'slug' => 'objetos-y-clases',
                'titulo' => 'Objetos y clases',
                'descripcion' => 'Programación orientada a objetos. Crea tus propios tipos de datos.',
                'icono' => '🏗️', 'dias_estimados' => 10, 'recompensa_base' => 35000, 'activo' => true,
            ],
            [
                'nivel' => 3, 'orden' => 2, 'slug' => 'python-y-el-internet',
                'titulo' => 'Python y el internet',
                'descripcion' => 'APIs, requests y JSON. Conecta tu programa con el mundo real.',
                'icono' => '🌐', 'dias_estimados' => 10, 'recompensa_base' => 35000, 'activo' => true,
            ],
            [
                'nivel' => 3, 'orden' => 3, 'slug' => 'hagamos-un-juego-visual',
                'titulo' => '¡Hagamos un juego visual!',
                'descripcion' => 'Turtle y Pygame. Crea tu primer videojuego real con Python.',
                'icono' => '🎮', 'dias_estimados' => 10, 'recompensa_base' => 35000, 'activo' => true,
            ],
            // Nivel 4 — Boss
            [
                'nivel' => 4, 'orden' => 1, 'slug' => 'proyecto-final',
                'titulo' => 'Proyecto Final Personal',
                'descripcion' => 'Tú eliges el problema, tú lo resuelves. Demuestra todo lo que aprendiste.',
                'icono' => '🏆', 'dias_estimados' => 14, 'recompensa_base' => 70000, 'activo' => true,
            ],
        ];

        foreach ($modulos as $data) {
            Modulo::create($data);
        }
    }
}
