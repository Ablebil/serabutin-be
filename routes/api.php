<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function (): void {
        Route::post('/register', [AuthController::class, 'register']);
        Route::get('/verify', [AuthController::class, 'verify']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth.jwt');
    });

    Route::prefix('users')->middleware('auth.jwt')->group(function (): void {
        Route::get('/me', [UserController::class, 'me']);
        Route::patch('/me', [UserController::class, 'update']);
    });
});