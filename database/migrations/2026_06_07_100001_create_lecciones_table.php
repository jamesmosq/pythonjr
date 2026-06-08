<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lecciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')->constrained('modulos')->cascadeOnDelete();
            $table->smallInteger('orden');
            $table->string('tipo', 30); // 'teoria' | 'ejemplo_codigo' | 'video' | 'tip'
            $table->string('titulo', 200)->nullable();
            $table->text('contenido');
            $table->string('lenguaje', 20)->default('python');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecciones');
    }
};
