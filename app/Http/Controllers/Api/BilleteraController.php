<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransaccionBilletera;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BilleteraController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $billetera = $user->billetera;

        $transacciones = TransaccionBilletera::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get()
            ->map(fn ($t) => [
                'id' => $t->id,
                'tipo' => $t->tipo,
                'descripcion' => $t->descripcion,
                'monto' => $t->monto,
                'created_at' => $t->created_at->toISOString(),
            ]);

        return response()->json([
            'success' => true,
            'data' => [
                'saldo_total' => $billetera?->saldo_total ?? 0,
                'saldo_pendiente' => $billetera?->saldo_pendiente ?? 0,
                'saldo_pagado' => $billetera?->saldo_pagado ?? 0,
                'transacciones' => $transacciones,
            ],
        ]);
    }
}
