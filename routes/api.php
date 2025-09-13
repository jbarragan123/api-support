<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login']);

// rutas protegidas
Route::middleware(['jwt.auth'])->group(function () {
    Route::post('/solicitudes', [SolicitudController::class, 'store'])->middleware('role:role_client');
    
    Route::get('/solicitudes', [SolicitudController::class, 'index']);
    Route::put('/solicitudes/{id}', [SolicitudController::class, 'update']);
    Route::get('/reportes/solicitudes', [ReporteController::class, 'index']);
});