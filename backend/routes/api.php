<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Middleware\HandleCors;

Route::post('/auth/login', [AuthController::class, 'login'])
    ->middleware(HandleCors::class); 

// rutas protegidas con JWT + CORS + rate limiting
Route::middleware(['jwt.verify', HandleCors::class, 'throttle:60,1'])->group(function () {

    Route::post('/solicitudes', [SolicitudController::class, 'store'])
        ->middleware('role:role_client')
        ->middleware('throttle:30,1'); // 30 requests/minuto

    Route::get('/solicitudes', [SolicitudController::class, 'index']);

    Route::put('/solicitudes/{id}', [SolicitudController::class, 'update'])
        ->middleware('throttle:30,1'); // 30 requests/minuto

    Route::get('/reportes/solicitudes', [ReporteController::class, 'index']);

});
