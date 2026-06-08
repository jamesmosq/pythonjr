<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RachaResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RachaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $racha = $request->user()->racha;

        return response()->json([
            'success' => true,
            'data' => $racha ? new RachaResource($racha) : [
                'dias_actuales' => 0,
                'dias_maximos' => 0,
                'ultima_actividad_at' => null,
                'activa_hoy' => false,
            ],
        ]);
    }
}
