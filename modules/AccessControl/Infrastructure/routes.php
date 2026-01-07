<?php

use Illuminate\Support\Facades\Route;
use Modules\AccessControl\Infrastructure\Http\Controllers\AuthController;

Route::prefix('api/auth')->group(function () {
    
    // Rota Pública: Login
    Route::post('login', [AuthController::class, 'login']);

    // Rotas Protegidas (Exigem Token)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });

});