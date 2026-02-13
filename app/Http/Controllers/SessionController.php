<?php

namespace App\Http\Controllers;

use App\Http\Resources\TrainingSessionResource;
use App\Models\TrainingSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\File;

class SessionController extends Controller
{
    public function show (TrainingSession $session)
    {
        $session->load(['sportType', 'trainingSummary', 'heartRateZones']);
        
        return Inertia::render('Diary/SessionDetail', [
            'session' => new TrainingSessionResource($session),
        ]);
    }

    public function rawData(TrainingSession $session)
    {
        if (! File::exists($session->file_path)) {
            abort(404, 'Raw data not found');
        }

        $rawData = json_decode(File::get($session->file_path), true);

        return response()->json($rawData);
    }
}
