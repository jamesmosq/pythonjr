<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ejercicios_completados', function (Blueprint $table) {
            $table->text('feedback_ia')->nullable()->after('respuesta_dada');
            $table->boolean('validado_por_ia')->default(false)->after('validado_por_padre');
            $table->foreignId('hackathon_id')->nullable()->after('modulo_id')
                ->constrained('hackathones')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ejercicios_completados', function (Blueprint $table) {
            $table->dropColumn(['feedback_ia', 'validado_por_ia', 'hackathon_id']);
        });
    }
};
