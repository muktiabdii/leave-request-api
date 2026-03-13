<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeaveRequestController;

// API Versioning
Route::prefix('v1')->group(function () {

    // auth
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::prefix('google')->group(function () {
            Route::get('/redirect', [AuthController::class, 'redirectToGoogle']);
            Route::get('/callback', [AuthController::class, 'handleGoogleCallback']);
        });
    });

    Route::middleware('auth:sanctum')->group(function () {

        // auth
        Route::prefix('auth')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
        });

        // user
        Route::get('/me', [UserController::class, 'me']);
        Route::patch('/me', [UserController::class, 'updateProfile']);
    });

    // leave request
    Route::middleware(['auth:sanctum', 'role:employee'])->group(function () {
        Route::prefix('leave-requests')->group(function () {
            Route::post('/', [LeaveRequestController::class, 'store']);
            Route::get('/', [LeaveRequestController::class, 'index']);
            Route::get('/{id}', [LeaveRequestController::class, 'show']);
            Route::patch('/{id}', [LeaveRequestController::class, 'update']);
            Route::patch('/{id}/cancel', [LeaveRequestController::class, 'cancel']);
        });
    });

    // admin leave request
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::prefix('admin/leave-requests')->group(function () {
            Route::get('/', [LeaveRequestController::class, 'indexAdmin']);
            Route::get('/{id}', [LeaveRequestController::class, 'showAdmin']);
            Route::patch('/{id}/approve', [LeaveRequestController::class, 'approve']);
        });
    });

});