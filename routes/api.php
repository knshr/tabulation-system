<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\ScoreController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public auth
    Route::post('auth/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:api')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);

        // Events
        Route::get('events', [EventController::class, 'index']);
        Route::get('events/{event}', [EventController::class, 'show']);

        // Scoring (Judge only)
        Route::middleware('role:judge')->group(function () {
            Route::get('events/{event}/scoring', [ScoreController::class, 'index']);
            Route::post('events/{event}/scores', [ScoreController::class, 'store']);
        });

        // View scores
        Route::get('events/{event}/scores', [ScoreController::class, 'scores']);
    });
});
