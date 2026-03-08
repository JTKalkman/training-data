<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\TrainingSessionController;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    Route::delete('/auth/logout', [AuthController::class, 'logout']);

    Route::get('profiles', [ProfileController::class, 'index']);
    // Route::delete('profiles/{}', []); // TODO: Soft deletes, keeps data intact.
    // Route::put('profiles/{}/restore', []); // TODO: Restore if deleted by mistake.

    Route::get('devices', [DeviceController::class, 'index']);

    Route::get('training-sessions', [TrainingSessionController::class, 'index']);
    Route::get('training-sessions/{trainingSession} ', [TrainingSessionController::class, 'show']);
    // Route::get('training-sessions/training-sessions/{id}/sample-data', []);
    // Route::get('/training-sessions/{id}/route', []);
    // Route::patch('/training-sessions/{id} ', []);
    // Route::get('training-sessions', []);
});

/**
 * TODO: 
 * 
 * Authenticated:
 * GET      /api/v1/training-sessions/{id}/sample-data                              The sample data for the current training session. Large json file from storage.
 * GET      /api/v1/training-sessions/{id}/route                                    The down sampled route data for the current training session. Large json file from storage.
 * PATCH    /api/v1/training-sessions/{id}                                          Update or add feedback or notes.                                    
 * GET      /api/v1/sport-types                                                     List of all supported sport types.
 */
