<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionPlataforma;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index(): JsonResponse
    {
        $config = ConfiguracionPlataforma::orderBy('clave')->get()
            ->mapWithKeys(fn ($c) => [$c->clave => [
                'valor'       => ConfiguracionPlataforma::valor($c->clave),
                'tipo'        => $c->tipo,
                'descripcion' => $c->descripcion,
            ]]);

        return response()->json(['success' => true, 'data' => $config]);
    }

    public function actualizar(Request $request): JsonResponse
    {
        $data = $request->validate([
            'configuracion'   => 'required|array',
            'configuracion.*' => 'present',
        ]);

        foreach ($data['configuracion'] as $clave => $valor) {
            ConfiguracionPlataforma::where('clave', $clave)
                ->update(['valor' => (string) $valor]);
        }

        return response()->json(['success' => true, 'message' => 'Configuración actualizada.']);
    }
}
