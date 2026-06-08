<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hackathones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creado_por')->constrained('users');
            $table->string('nombre');
            $table->string('tipo')->default('sprint'); // sprint, maraton, maestro, tematico
            $table->integer('recompensa_base');
            $table->float('multiplicador_velocidad')->default(1.3);
            $table->float('multiplicador_perfecto')->default(1.5);
            $table->integer('tiempo_limite_horas')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->string('estado')->default('pendiente'); // pendiente, activo, finalizado, cancelado
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hackathones');
    }
};
