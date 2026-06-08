<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Logro;
use App\Models\LogroUsuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogrosController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $desbloqueados = LogroUsuario::where('user_id', $user->id)
            ->get()
            ->keyBy('logro_id');

        $logros = Logro::all()->map(function (Logro $logro) use ($desbloqueados) {
            $lu = $desbloqueados->get($logro->id);
            return [
                'slug' => $logro->slug,
                'nombre' => $logro->nombre,
                'descripcion' => $logro->descripcion,
                'icono' => $logro->icono,
                'tipo' => $logro->tipo,
                'desbloqueado' => (bool) $lu,
                'desbloqueado_at' => $lu?->desbloqueado_at?->toISOString(),
            ];
        });

        $total = $logros->count();
        $obtenidos = $logros->where('desbloqueado', true)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'logros' => $logros->values(),
                'total' => $total,
                'obtenidos' => $obtenidos,
            ],
        ]);
    }
}
