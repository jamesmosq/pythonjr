<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logros', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 80)->unique();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->string('icono', 100)->nullable();
            // 'progreso' | 'velocidad' | 'constancia' | 'perfeccion' | 'especial'
            $table->string('tipo', 30)->nullable();
            $table->integer('condicion_valor')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logros');
    }
};
