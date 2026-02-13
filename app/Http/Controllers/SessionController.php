<?php

namespace App\Http\Controllers;

use App\Http\Resources\TrainingSessionResource;
use App\Models\TrainingSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SessionController extends Controller
{
    public function show (TrainingSession $session)
    {
        $session->load(['sportType', 'trainingSummary', 'heartRateZones']);
        
        return Inertia::render('Diary/SessionDetail', [
            'session' => new TrainingSessionResource($session),
        ]);
    }
}
