<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hackathon_ejercicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hackathon_id')->constrained('hackathones')->onDelete('cascade');
            $table->foreignId('ejercicio_id')->constrained('ejercicios');
            $table->integer('orden');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hackathon_ejercicios');
    }
};
