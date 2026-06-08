<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Billetera;
use App\Models\Modulo;
use App\Models\ProgresoModulo;
use App\Models\Racha;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function registro(Request $request): JsonResponse
    {
        // Valida el token secreto antes de cualquier otra cosa
        $registroToken = config('app.registro_token');
        if (! $registroToken || $request->input('token') !== $registroToken) {
            return response()->json(['success' => false, 'message' => 'Token inválido.'], 403);
        }

        $request->validate([
            'name'     => 'required|string|max:60',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'avatar'   => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'estudiante',
            'avatar'   => $request->avatar ?? '🧑‍🎓',
        ]);

        Billetera::create([
            'user_id'          => $user->id,
            'saldo_total'      => 0,
            'saldo_pendiente'  => 0,
            'saldo_pagado'     => 0,
        ]);

        Racha::create([
            'user_id'            => $user->id,
            'dias_actuales'      => 0,
            'dias_maximos'       => 0,
            'ultima_actividad_at'=> null,
        ]);

        // Desbloquear el primer módulo para que el estudiante pueda empezar
        $primerModulo = Modulo::where('activo', true)->orderBy('nivel')->orderBy('orden')->first();
        if ($primerModulo) {
            ProgresoModulo::create([
                'user_id'   => $user->id,
                'modulo_id' => $primerModulo->id,
                'estado'    => 'disponible',
            ]);
        }

        Auth::login($user);

        return response()->json([
            'success' => true,
            'data' => [
                'id'     => $user->id,
                'name'   => $user->name,
                'email'  => $user->email,
                'role'   => $user->role,
                'avatar' => $user->avatar,
            ],
            'message' => '¡Bienvenido a PythonJr, ' . $user->name . '!',
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales no son correctas.'],
            ]);
        }

        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'avatar' => $user->avatar,
            ],
            'message' => '¡Bienvenido, ' . $user->name . '!',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada.',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'avatar' => $user->avatar,
            ],
        ]);
    }
}
