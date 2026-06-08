<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\BonoSorpresa;
use App\Models\User;
use App\Services\BilleteraService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BonoSorpresaController extends Controller
{
    public function __construct(private BilleteraService $billeteraService) {}

    public function enviar(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'monto'   => 'required|integer|min:1000|max:500000',
            'mensaje' => 'nullable|string|max:500',
        ]);

        $estudiante = User::findOrFail($data['user_id']);

        BonoSorpresa::create([
            'admin_id' => $request->user()->id,
            'user_id'  => $data['user_id'],
            'monto'    => $data['monto'],
            'mensaje'  => $data['mensaje'] ?? null,
        ]);

        $descripcion = $data['mensaje']
            ? "🎁 Papá dice: \"{$data['mensaje']}\""
            : '🎁 ¡Bono sorpresa de papá!';

        $this->billeteraService->acreditar(
            $estudiante,
            $data['monto'],
            'bono_sorpresa',
            null, null,
            $descripcion
        );

        return response()->json([
            'success' => true,
            'message' => "Bono de \${$data['monto']} enviado a {$estudiante->name}.",
        ]);
    }

    public function historial(Request $request): JsonResponse
    {
        $bonos = BonoSorpresa::where('admin_id', $request->user()->id)
            ->latest('created_at')
            ->limit(20)
            ->get()
            ->map(fn ($b) => [
                'monto'      => $b->monto,
                'mensaje'    => $b->mensaje,
                'created_at' => $b->created_at,
            ]);

        return response()->json(['success' => true, 'data' => $bonos]);
    }
}
