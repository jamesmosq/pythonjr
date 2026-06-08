<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('desafios_dia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ejercicio_id')->constrained('ejercicios')->cascadeOnDelete();
            $table->date('fecha')->unique();
            $table->integer('recompensa')->default(5000);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desafios_dia');
    }
};
