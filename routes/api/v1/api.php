<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\SportTypeController;
use App\Http\Controllers\Api\V1\TrainingSessionController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    Route::delete('/auth/logout', [AuthController::class, 'logout']);

    Route::get('profiles', [ProfileController::class, 'index']);
    // Route::delete('profiles/{}', []); // TODO: Soft deletes, keeps data intact.
    // Route::put('profiles/{}/restore', []); // TODO: Restore if deleted by mistake.

    Route::get('devices', [DeviceController::class, 'index']);

    Route::get('training-sessions', [TrainingSessionController::class, 'index']);
    Route::get('training-sessions/{trainingSession} ', [TrainingSessionController::class, 'show']);
    Route::get('training-sessions/{trainingSession}/sample-data', [TrainingSessionController::class, 'sampleData']);
    Route::get('training-sessions/{trainingSession}/route-data', [TrainingSessionController::class, 'routeData']);
    // Route::patch('/training-sessions/{id} ', []); // TODO: Update or add feedback or notes.

    Route::get('sport-types', [SportTypeController::class, 'index']);
});
