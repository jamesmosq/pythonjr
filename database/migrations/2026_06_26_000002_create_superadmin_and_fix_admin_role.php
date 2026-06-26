<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        // Crear superadmin real si no existe
        if (! DB::table('users')->where('email', 'jamesmosqr@gmail.com')->exists()) {
            DB::table('users')->insert([
                'name'       => 'James',
                'email'      => 'jamesmosqr@gmail.com',
                'password'   => Hash::make('superadmin123'),
                'role'       => 'superadmin',
                'avatar'     => '🦸',
                'parent_id'  => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('users')
                ->where('email', 'jamesmosqr@gmail.com')
                ->update(['role' => 'superadmin']);
        }

        // Downgrade: admin@pythonjr.com vuelve a ser admin (papá de familia, no superadmin)
        DB::table('users')
            ->where('email', 'admin@pythonjr.com')
            ->update(['role' => 'admin']);
    }

    public function down(): void
    {
        DB::table('users')->where('email', 'jamesmosqr@gmail.com')->delete();

        DB::table('users')
            ->where('email', 'admin@pythonjr.com')
            ->update(['role' => 'superadmin']);
    }
};
