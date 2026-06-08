<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ejercicios_completados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ejercicio_id')->constrained('ejercicios')->cascadeOnDelete();
            $table->foreignId('modulo_id')->constrained('modulos')->cascadeOnDelete();
            $table->smallInteger('intento')->default(1);
            $table->text('respuesta_dada')->nullable();
            $table->boolean('es_correcto')->default(false);
            $table->boolean('es_perfecto')->default(false);
            $table->boolean('validado_por_padre')->default(false);
            $table->integer('recompensa_ganada')->default(0);
            $table->timestampTz('completado_at')->useCurrent();
            $table->unique(['user_id', 'ejercicio_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ejercicios_completados');
    }
};
