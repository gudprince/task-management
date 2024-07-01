<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;


Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
    });
  
    Route::middleware(['auth:api'])->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::apiResource('tasks', TaskController::class);
    });
});

Route::get('/authenticated/json', function () {
    return response()->json(['message' => 'Unauthorized, please login'], 401);
})->name('api.authenticated.json');

