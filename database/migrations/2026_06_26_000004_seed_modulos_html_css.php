<?php

use App\Models\Modulo;
use Database\Seeders\ModuloCSSSeeder;
use Database\Seeders\ModuloHTMLSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (Modulo::where('slug', 'html-construyendo-la-web')->exists()) {
            return;
        }

        // 1. Crear los registros de módulo (lo que haría ModuloSeeder)
        Modulo::create([
            'nivel' => 5, 'orden' => 1, 'slug' => 'html-construyendo-la-web',
            'titulo' => 'HTML: Construyendo la Web',
            'descripcion' => 'El lenguaje que da estructura a todas las páginas web. Crea tu primera página desde cero.',
            'icono' => '🌐', 'dias_estimados' => 7, 'recompensa_base' => 5000, 'activo' => true,
        ]);

        Modulo::create([
            'nivel' => 5, 'orden' => 2, 'slug' => 'css-disenando-con-estilo',
            'titulo' => 'CSS: Diseñando con Estilo',
            'descripcion' => 'Dales vida y color a tus páginas HTML. Colores, tipografías, flexbox y más.',
            'icono' => '🎨', 'dias_estimados' => 7, 'recompensa_base' => 5000, 'activo' => true,
        ]);

        // 2. Poblar lecciones y ejercicios
        (new ModuloHTMLSeeder())->run();
        (new ModuloCSSSeeder())->run();
    }

    public function down(): void
    {
        foreach (['html-construyendo-la-web', 'css-disenando-con-estilo'] as $slug) {
            $mod = Modulo::where('slug', $slug)->first();
            if ($mod) {
                $mod->ejercicios()->each(fn ($e) => $e->opciones()->delete());
                $mod->ejercicios()->delete();
                $mod->lecciones()->delete();
                $mod->delete();
            }
        }
    }
};
