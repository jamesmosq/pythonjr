<?php

namespace Database\Seeders;

use App\Models\Logro;
use Illuminate\Database\Seeder;

class LogroSeeder extends Seeder
{
    public function run(): void
    {
        $logros = [
            // Inicio
            ['slug' => 'primer-paso', 'nombre' => '¡Primer paso!', 'descripcion' => 'Completaste tu primer ejercicio', 'icono' => '👣', 'tipo' => 'progreso', 'condicion_valor' => 1],
            ['slug' => 'bienvenido-nivel1', 'nombre' => '¡Bienvenido, Aprendiz!', 'descripcion' => 'Completaste el Módulo 1', 'icono' => '🐍', 'tipo' => 'progreso', 'condicion_valor' => null],

            // Perfección
            ['slug' => 'tiro-perfecto', 'nombre' => '¡Tiro perfecto!', 'descripcion' => 'Resolviste 1 ejercicio correcto al primer intento', 'icono' => '⭐', 'tipo' => 'perfeccion', 'condicion_valor' => 1],
            ['slug' => 'francotirador', 'nombre' => 'Francotirador', 'descripcion' => 'Resolviste 5 ejercicios perfectos', 'icono' => '🎯', 'tipo' => 'perfeccion', 'condicion_valor' => 5],
            ['slug' => 'ojo-de-aguila', 'nombre' => 'Ojo de águila', 'descripcion' => 'Resolviste 15 ejercicios perfectos', 'icono' => '🦅', 'tipo' => 'perfeccion', 'condicion_valor' => 15],

            // Constancia (racha)
            ['slug' => 'racha-3', 'nombre' => '¡En racha!', 'descripcion' => 'Primera racha de 3 días seguidos', 'icono' => '🔥', 'tipo' => 'constancia', 'condicion_valor' => 3],
            ['slug' => 'semana-completa', 'nombre' => 'Semana completa', 'descripcion' => 'Primera racha de 7 días seguidos', 'icono' => '📅', 'tipo' => 'constancia', 'condicion_valor' => 7],
            ['slug' => 'imparable', 'nombre' => '¡Imparable!', 'descripcion' => 'Racha de 14 días seguidos', 'icono' => '⚡', 'tipo' => 'constancia', 'condicion_valor' => 14],

            // Velocidad
            ['slug' => 'rapido', 'nombre' => '¡Rápido!', 'descripcion' => 'Completaste un módulo en menos de 5 días', 'icono' => '🚀', 'tipo' => 'velocidad', 'condicion_valor' => 5],
            ['slug' => 'rayo', 'nombre' => '¡Un rayo!', 'descripcion' => 'Completaste un nivel completo en menos de 2 semanas', 'icono' => '⚡', 'tipo' => 'velocidad', 'condicion_valor' => 14],

            // Progreso
            ['slug' => 'explorer', 'nombre' => 'Explorador', 'descripcion' => 'Completaste el Nivel 1 completo', 'icono' => '🗺️', 'tipo' => 'progreso', 'condicion_valor' => null],
            ['slug' => 'constructor', 'nombre' => 'Constructor', 'descripcion' => 'Completaste el Nivel 2 completo', 'icono' => '🏗️', 'tipo' => 'progreso', 'condicion_valor' => null],
            ['slug' => 'arquitecto', 'nombre' => 'Arquitecto', 'descripcion' => 'Completaste el Nivel 3 completo', 'icono' => '🏛️', 'tipo' => 'progreso', 'condicion_valor' => null],
            ['slug' => 'master', 'nombre' => '¡Master Python!', 'descripcion' => 'Completaste el Proyecto Final', 'icono' => '🏆', 'tipo' => 'progreso', 'condicion_valor' => null],

            // Especiales
            ['slug' => 'fizzbuzz-hero', 'nombre' => 'FizzBuzz Hero', 'descripcion' => 'Completaste el clásico desafío FizzBuzz', 'icono' => '🦸', 'tipo' => 'especial', 'condicion_valor' => null],
            ['slug' => 'api-caller', 'nombre' => 'API Caller', 'descripcion' => 'Hiciste tu primera consulta a una API real', 'icono' => '🌐', 'tipo' => 'especial', 'condicion_valor' => null],
            ['slug' => 'game-creator', 'nombre' => 'Game Creator', 'descripcion' => 'Creaste tu primer juego visual', 'icono' => '🎮', 'tipo' => 'especial', 'condicion_valor' => null],
            ['slug' => 'github-star',      'nombre' => 'GitHub Star',       'descripcion' => 'Subiste tu proyecto a GitHub',                      'icono' => '⭐', 'tipo' => 'especial',  'condicion_valor' => null],

            // Git/GitHub
            ['slug' => 'primer-commit',    'nombre' => '¡Primer commit!',   'descripcion' => 'Hiciste tu primer commit en Git',                     'icono' => '💾', 'tipo' => 'especial',  'condicion_valor' => null],
            ['slug' => 'en-la-nube',       'nombre' => 'En la nube ☁️',     'descripcion' => 'Subiste tu primer repositorio a GitHub',              'icono' => '☁️', 'tipo' => 'especial',  'condicion_valor' => null],
            ['slug' => 'control-total',    'nombre' => 'Control total',      'descripcion' => 'Completaste el módulo de Git y GitHub',               'icono' => '🌿', 'tipo' => 'progreso',  'condicion_valor' => null],
            ['slug' => 'commit-epico',     'nombre' => 'Commit épico',       'descripcion' => '5 ejercicios de terminal perfectos',                  'icono' => '✍️', 'tipo' => 'perfeccion', 'condicion_valor' => 5],

            // Hackathon
            ['slug' => 'hackathon-rookie', 'nombre' => 'Hackathon Rookie',   'descripcion' => 'Completaste tu primer hackathon',                     'icono' => '🏁', 'tipo' => 'especial',  'condicion_valor' => null],
            ['slug' => 'hackathon-pro',    'nombre' => 'Hackathon Pro',      'descripcion' => 'Completaste un hackathon con todos los ejercicios perfectos', 'icono' => '🏆', 'tipo' => 'especial', 'condicion_valor' => null],

            // Meta semanal
            ['slug' => 'meta-semanal',     'nombre' => 'Meta cumplida',      'descripcion' => 'Completaste la meta semanal de ejercicios',           'icono' => '🎯', 'tipo' => 'constancia', 'condicion_valor' => null],
        ];

        foreach ($logros as $data) {
            Logro::create($data);
        }
    }
}
