<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LibroController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\MetricaController;
use App\Http\Middleware\IsUserAuth;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('libros', LibroController::class)->only(['index', 'show']);
Route::get('/libros/{id}/ultimoPrestamo', [PrestamoController::class, 'ultimoPrestamo']);

Route::middleware([IsUserAuth::class])->group(function () {
    
  // Rutas de autenticación
  Route::get('/me', [AuthController::class, 'user']);
  Route::post('/logout', [AuthController::class, 'logout']);
  
  // Rutas protegidas
  Route::apiResource('libros', LibroController::class)->except(['index', 'show']);
  Route::apiResource('prestamos', PrestamoController::class);

  Route::get('/metricas', [MetricaController::class, 'resumen']);
});
