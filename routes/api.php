<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('admin')->group(function () {
        Route::get('all', [AdminController::class, 'getAll']);
        Route::post('register', [AdminController::class, 'register']);
        Route::put('update/{id}', [AdminController::class, 'update']);
        Route::delete('delete/{id}', [AdminController::class, 'delete']);
    });

    Route::prefix('client')->group(function () {
        Route::get('all', [ClientController::class, 'getAll']);
        Route::post('register', [ClientController::class, 'register']);
        Route::put('update/{id}', [ClientController::class, 'update']);
        Route::delete('delete/{id}', [ClientController::class, 'delete']);
    });
});
