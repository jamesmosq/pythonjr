<?php

use App\Http\Controllers\Api\Admin\AdminDashboardController;
use App\Http\Controllers\Api\SuperAdmin\SuperAdminController;
use App\Http\Controllers\Api\Admin\AdminPerfilController;
use App\Http\Controllers\Api\Admin\BonoSorpresaController;
use App\Http\Controllers\Api\Admin\ConfiguracionController;
use App\Http\Controllers\Api\Admin\HackathonAdminController;
use App\Http\Controllers\Api\Admin\ValidacionController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BilleteraController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DesafioDiaController;
use App\Http\Controllers\Api\EjercicioController;
use App\Http\Controllers\Api\HackathonController;
use App\Http\Controllers\Api\LogrosController;
use App\Http\Controllers\Api\ModuloController;
use App\Http\Controllers\Api\RachaController;
use Illuminate\Support\Facades\Route;

// Auth
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/registro', [AuthController::class, 'registro']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

// Rutas del estudiante
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/modulos', [ModuloController::class, 'index']);
    Route::get('/modulos/{slug}', [ModuloController::class, 'show']);

    Route::get('/ejercicios/{ejercicio}', [EjercicioController::class, 'show']);
    Route::post('/ejercicios/{ejercicio}/intentar', [EjercicioController::class, 'intentar'])
        ->middleware('throttle:10,1');

    Route::get('/billetera', [BilleteraController::class, 'index']);
    Route::get('/racha', [RachaController::class, 'index']);
    Route::get('/logros', [LogrosController::class, 'index']);

    Route::get('/desafio-del-dia', [DesafioDiaController::class, 'index']);
    Route::post('/desafio-del-dia/intentar', [DesafioDiaController::class, 'intentar'])
        ->middleware('throttle:10,1');

    Route::get('/hackathon/activo', [HackathonController::class, 'activo']);
});

// Rutas del admin (padre)
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);
    Route::get('/estudiantes', [AdminDashboardController::class, 'estudiantes']);
    Route::put('/perfil', [AdminPerfilController::class, 'update']);
    Route::get('/pendientes', [ValidacionController::class, 'pendientes']);
    Route::post('/validar/{completado}', [ValidacionController::class, 'validar']);
    Route::post('/pagar', [ValidacionController::class, 'pagar']);

    Route::get('/configuracion', [ConfiguracionController::class, 'index']);
    Route::put('/configuracion', [ConfiguracionController::class, 'actualizar']);

    Route::post('/bono-sorpresa', [BonoSorpresaController::class, 'enviar']);
    Route::get('/bono-sorpresa/historial', [BonoSorpresaController::class, 'historial']);

    Route::get('/hackathon', [HackathonAdminController::class, 'index']);
    Route::post('/hackathon/activar', [HackathonAdminController::class, 'activar']);
    Route::delete('/hackathon/{hackathon}', [HackathonAdminController::class, 'cancelar']);
});

// Rutas del superadmin (dueño de la plataforma)
Route::middleware(['auth:sanctum', 'superadmin'])->prefix('superadmin')->group(function () {
    Route::get('/stats',                            [SuperAdminController::class, 'stats']);
    Route::get('/familias',                         [SuperAdminController::class, 'familias']);
    Route::get('/modulos',                          [SuperAdminController::class, 'modulos']);
    Route::put('/modulos/{modulo}/toggle',          [SuperAdminController::class, 'toggleModulo']);
});
