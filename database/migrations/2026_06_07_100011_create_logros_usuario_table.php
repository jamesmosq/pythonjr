<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logros_usuario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('logro_id')->constrained('logros')->cascadeOnDelete();
            $table->timestampTz('desbloqueado_at')->useCurrent();
            $table->unique(['user_id', 'logro_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logros_usuario');
    }
};
