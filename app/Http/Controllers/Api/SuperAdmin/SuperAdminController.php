<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Models\Modulo;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminController
{
    public function stats(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_familias'       => User::where('role', 'admin')->count(),
                'total_estudiantes'    => User::where('role', 'estudiante')->count(),
                'actividad_semana'     => \App\Models\EjercicioCompletado::where('es_correcto', true)
                    ->where('completado_at', '>=', now()->startOfWeek())
                    ->count(),
                'nuevas_familias_mes'  => User::where('role', 'admin')
                    ->where('created_at', '>=', now()->startOfMonth())
                    ->count(),
            ],
        ]);
    }

    public function familias(): JsonResponse
    {
        $familias = User::where('role', 'admin')
            ->with(['estudiantes:id,name,avatar,parent_id'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($admin) => [
                'id'          => $admin->id,
                'nombre'      => $admin->name,
                'email'       => $admin->email,
                'avatar'      => $admin->avatar,
                'activo'      => (bool) $admin->activo,
                'creado'      => $admin->created_at->format('d/m/Y'),
                'estudiantes' => $admin->estudiantes->map(fn ($e) => [
                    'id'     => $e->id,
                    'nombre' => $e->name,
                    'avatar' => $e->avatar,
                ]),
            ]);

        return response()->json(['success' => true, 'data' => $familias]);
    }

    public function toggleFamilia(User $user): JsonResponse
    {
        if ($user->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Solo se pueden gestionar cuentas de familia.'], 422);
        }

        $user->activo = ! $user->activo;
        $user->save();

        // Revocar tokens de todos en la familia si se desactiva
        if (! $user->activo) {
            $user->tokens()->delete();
            User::where('parent_id', $user->id)->each(fn ($e) => $e->tokens()->delete());
        }

        return response()->json([
            'success' => true,
            'data'    => ['activo' => (bool) $user->activo],
        ]);
    }

    public function resetPasswordFamilia(User $user): JsonResponse
    {
        if ($user->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Solo se pueden resetear contraseñas de administradores de familia.'], 422);
        }

        $nueva = Str::random(4) . rand(10, 99) . Str::random(2);
        $user->password = Hash::make($nueva);
        $user->save();
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'data'    => ['nueva_contrasena' => $nueva],
        ]);
    }

    public function modulos(): JsonResponse
    {
        $modulos = Modulo::orderBy('nivel')->orderBy('orden')
            ->get()
            ->map(fn ($m) => [
                'id'     => $m->id,
                'titulo' => $m->titulo,
                'nivel'  => $m->nivel,
                'icono'  => $m->icono,
                'activo' => (bool) $m->activo,
            ]);

        return response()->json(['success' => true, 'data' => $modulos]);
    }

    public function toggleModulo(Modulo $modulo): JsonResponse
    {
        $modulo->activo = ! $modulo->activo;
        $modulo->save();

        return response()->json([
            'success' => true,
            'data'    => ['activo' => (bool) $modulo->activo],
        ]);
    }
}
