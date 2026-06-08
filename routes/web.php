<?php

use Illuminate\Support\Facades\Route;

/*
 * Sirve el SPA de React para todas las rutas que no sean API ni archivos estáticos.
 * Las rutas de la API (api.php) y Sanctum tienen prioridad y no llegan aquí.
 */
Route::get('/{any}', function () {
    $spa = public_path('spa/index.html');
    if (file_exists($spa)) {
        return response()->file($spa);
    }
    // En desarrollo local el React corre en localhost:5173
    return redirect('http://localhost:5173');
})->where('any', '.*');
