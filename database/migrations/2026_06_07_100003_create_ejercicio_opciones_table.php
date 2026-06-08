<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ejercicio_opciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ejercicio_id')->constrained('ejercicios')->cascadeOnDelete();
            $table->text('texto');
            $table->boolean('es_correcta')->default(false);
            $table->smallInteger('orden')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ejercicio_opciones');
    }
};
