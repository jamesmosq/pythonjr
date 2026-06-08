<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transacciones_billetera', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // 'modulo_base' | 'ejercicio' | 'perfecto' | 'racha_3d' | 'racha_7d' |
            // 'velocidad_modulo' | 'velocidad_nivel' | 'desafio_dia' | 'bono_nivel' | 'pago_padre'
            $table->string('tipo', 40);
            $table->string('descripcion', 255)->nullable();
            $table->integer('monto'); // positivo=ganancia, negativo=pago
            $table->unsignedBigInteger('referencia_id')->nullable();
            $table->string('referencia_tipo', 50)->nullable();
            $table->timestampTz('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transacciones_billetera');
    }
};
