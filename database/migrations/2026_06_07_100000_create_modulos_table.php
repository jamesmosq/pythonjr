<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modulos', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('nivel');
            $table->smallInteger('orden');
            $table->string('slug', 80)->unique();
            $table->string('titulo', 150);
            $table->text('descripcion')->nullable();
            $table->string('icono', 50)->nullable();
            $table->smallInteger('dias_estimados')->default(7);
            $table->integer('recompensa_base');
            $table->boolean('activo')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modulos');
    }
};
