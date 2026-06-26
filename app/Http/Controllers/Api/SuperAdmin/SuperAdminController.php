<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Models\Modulo;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
                'creado'      => $admin->created_at->format('d/m/Y'),
                'estudiantes' => $admin->estudiantes->map(fn ($e) => [
                    'id'     => $e->id,
                    'nombre' => $e->name,
                    'avatar' => $e->avatar,
                ]),
            ]);

        return response()->json(['success' => true, 'data' => $familias]);
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
