<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EsSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->role !== 'superadmin') {
            return response()->json(['success' => false, 'message' => 'Acceso denegado.'], 403);
        }

        return $next($request);
    }
}
