<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rachas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->smallInteger('dias_actuales')->default(0);
            $table->smallInteger('dias_maximos')->default(0);
            $table->date('ultima_actividad_at')->nullable();
            $table->boolean('racha_3d_cobrada')->default(false);
            $table->boolean('racha_7d_cobrada')->default(false);
            $table->timestampTz('updated_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rachas');
    }
};
