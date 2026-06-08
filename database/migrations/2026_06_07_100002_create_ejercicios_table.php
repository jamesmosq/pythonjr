<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ejercicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')->constrained('modulos')->cascadeOnDelete();
            $table->smallInteger('orden');
            // 'quiz_opcion' | 'quiz_texto' | 'codigo_libre' | 'mini_proyecto' | 'desafio_dia'
            $table->string('tipo', 30);
            $table->string('titulo', 200);
            $table->text('enunciado');
            $table->text('codigo_base')->nullable();
            $table->text('solucion')->nullable();
            $table->text('respuesta_correcta')->nullable();
            $table->boolean('es_obligatorio')->default(true);
            $table->integer('recompensa_ejercicio')->default(2000);
            $table->integer('recompensa_perfecto')->default(3000);
            $table->text('pista')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ejercicios');
    }
};
