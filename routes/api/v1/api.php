<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProfileController;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    Route::delete('/auth/logout', [AuthController::class, 'logout']);

    Route::get('profiles', [ProfileController::class, 'index']);
    // Route::delete('profiles/{}', []); // TODO
    // Route::put('profiles/{}/restore', []); // TODO
});

/**
 * TODO: 
 * 
 * Rate limiting.
 * 
 * Authenticated:
 * GET      /api/v1/me/devices                                                      The users devices. No pagination because we expect a small number of devices.
 * GET      /api/v1/training-sessions                                               All training sessions in the last 30 days with sports types and summaries.
 * GET      /api/v1/training-sessions?from=2026-03-01&to=2026-03-07&sport=running   All training sessions with filtering. Paginated.
 * GET      /api/v1/training-sessions/{id}                                          The current training session with sport type, training summary and heart rate zones.
 * GET      /api/v1/training-sessions/{id}/sample-data                              The sample data for the current training session. Large json file from storage.
 * GET      /api/v1/training-sessions/{id}/route                                    The down sampled route data for the current training session. Large json file from storage.
 * PATCH    /api/v1/training-sessions/{id}                                          Update or add feedback or notes.
 * DELETE   /api/v1/me/profiles/{id}                                                Soft deletes, keeps data intact.
 * PUT      /api/v1/me/profiles/{id}/restore                                        Restore if deleted by mistake.
 */
