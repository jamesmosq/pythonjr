<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hackathon_participaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hackathon_id')->constrained('hackathones')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->integer('ejercicios_completados')->default(0);
            $table->integer('ejercicios_perfectos')->default(0);
            $table->integer('recompensa_ganada')->default(0);
            $table->text('reporte_ia')->nullable();
            $table->timestamp('finalizado_at')->nullable();
            $table->timestamps();
            $table->unique(['hackathon_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hackathon_participaciones');
    }
};
