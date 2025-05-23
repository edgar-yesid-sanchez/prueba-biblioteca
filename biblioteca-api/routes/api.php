<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LibroController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PrestamoController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    
    // Rutas de autenticación
    Route::get('/user', [AuthController::class, 'authUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Rutas protegidas
    Route::apiResource('libros', LibroController::class);
    Route::apiResource('prestamos', PrestamoController::class);
});
 