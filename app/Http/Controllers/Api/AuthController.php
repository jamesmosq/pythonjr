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
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function registro(Request $request): JsonResponse
    {
        $registroToken = config('app.registro_token');
        if (! $registroToken || $request->input('token') !== $registroToken) {
            return response()->json(['success' => false, 'message' => 'Token inválido.'], 403);
        }

        $request->validate([
            'padre_name'     => 'required|string|max:60',
            'padre_email'    => 'required|email|unique:users,email',
            'padre_password' => 'required|min:6',
            'hijo_name'      => 'required|string|max:60',
            'hijo_email'     => 'required|email|unique:users,email',
            'hijo_password'  => 'required|min:6',
            'hijo_avatar'    => 'nullable|string|max:20',
        ]);

        // Crear el padre (admin de esta familia)
        $padre = User::create([
            'name'      => $request->padre_name,
            'email'     => $request->padre_email,
            'password'  => Hash::make($request->padre_password),
            'role'      => 'admin',
            'avatar'    => '👨‍💻',
            'parent_id' => null,
        ]);

        // Crear el hijo (estudiante) vinculado al padre
        $hijo = User::create([
            'name'      => $request->hijo_name,
            'email'     => $request->hijo_email,
            'password'  => Hash::make($request->hijo_password),
            'role'      => 'estudiante',
            'avatar'    => $request->hijo_avatar ?? '🧑‍🎓',
            'parent_id' => $padre->id,
        ]);

        Billetera::create([
            'user_id'         => $hijo->id,
            'saldo_total'     => 0,
            'saldo_pendiente' => 0,
            'saldo_pagado'    => 0,
        ]);

        Racha::create([
            'user_id'             => $hijo->id,
            'dias_actuales'       => 0,
            'dias_maximos'        => 0,
            'ultima_actividad_at' => null,
        ]);

        $primerModulo = Modulo::where('activo', true)->orderBy('nivel')->orderBy('orden')->first();
        if ($primerModulo) {
            ProgresoModulo::create([
                'user_id'   => $hijo->id,
                'modulo_id' => $primerModulo->id,
                'estado'    => 'disponible',
            ]);
        }

        // El padre queda logueado con su token
        $padre->tokens()->delete();
        $token = $padre->createToken('spa')->plainTextToken;

        return response()->json([
            'success' => true,
            'data'    => [
                'id'     => $padre->id,
                'name'   => $padre->name,
                'email'  => $padre->email,
                'role'   => $padre->role,
                'avatar' => $padre->avatar,
            ],
            'token'   => $token,
            'message' => '¡Familia registrada! Bienvenido, ' . $padre->name . '.',
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales no son correctas.'],
            ]);
        }

        if (! $user->activo) {
            throw ValidationException::withMessages([
                'email' => ['Tu cuenta ha sido desactivada. Contacta al administrador.'],
            ]);
        }

        if ($user->role === 'estudiante' && $user->parent_id) {
            $padre = User::find($user->parent_id);
            if ($padre && ! $padre->activo) {
                throw ValidationException::withMessages([
                    'email' => ['La cuenta de tu familia está desactivada. Contacta al administrador.'],
                ]);
            }
        }

        // Revocar tokens previos y emitir uno nuevo
        $user->tokens()->delete();
        $token = $user->createToken('spa')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'id'     => $user->id,
                'name'   => $user->name,
                'email'  => $user->email,
                'role'   => $user->role,
                'avatar' => $user->avatar,
            ],
            'token'   => $token,
            'message' => '¡Bienvenido, ' . $user->name . '!',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

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
