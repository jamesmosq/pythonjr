<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminPerfilController extends Controller
{
    public function update(Request $request): JsonResponse
    {
        $admin = $request->user();

        $request->validate([
            'name'     => 'required|string|max:60',
            'email'    => 'required|email|unique:users,email,' . $admin->id,
            'avatar'   => 'nullable|string',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $admin->name  = $request->name;
        $admin->email = $request->email;

        if ($request->filled('avatar')) {
            $admin->avatar = $request->avatar;
        }
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return response()->json([
            'success' => true,
            'data'    => [
                'id'     => $admin->id,
                'name'   => $admin->name,
                'email'  => $admin->email,
                'role'   => $admin->role,
                'avatar' => $admin->avatar,
            ],
            'message' => 'Perfil actualizado.',
        ]);
    }
}
