<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('parent_id')
                ->nullable()
                ->after('role')
                ->constrained('users')
                ->nullOnDelete();
        });

        // Promover admins existentes a superadmin
        DB::table('users')->where('role', 'admin')->update(['role' => 'superadmin']);

        // Vincular estudiantes existentes al primer superadmin
        $superadminId = DB::table('users')->where('role', 'superadmin')->value('id');
        if ($superadminId) {
            DB::table('users')
                ->where('role', 'estudiante')
                ->whereNull('parent_id')
                ->update(['parent_id' => $superadminId]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });

        DB::table('users')->where('role', 'superadmin')->update(['role' => 'admin']);
    }
};
