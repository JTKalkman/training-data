<?php

namespace App\Http\Controllers;

use App\Http\Resources\TrainingSessionResource;
use App\Models\TrainingSession;
use App\Support\ChartData;
use App\Support\MapData;
use Inertia\Inertia;

class TrainingSessionController extends Controller
{
    public function show(TrainingSession $session)
    {
        $this->authorize('view', $session);
        $session->load(['sportType', 'trainingSummary', 'heartRateZones']);
        $previousSession = $session->previousSession();
        $nextSession = $session->nextSession();

        return Inertia::render('TrainingSession', [
            'trainingSession' => new TrainingSessionResource($session),
            'navigation' => [
                'prev' => [
                    'id' => $previousSession?->id,
                    'url' => $previousSession ? route('training-sessions.session', $previousSession) : null,
                ],
                'next' => [
                    'id' => $nextSession?->id,
                    'url' => $nextSession ? route('training-sessions.session', $nextSession) : null,
                ],
            ],
        ]);
    }

    public function sampleData(TrainingSession $session)
    {
        $this->authorize('view', $session);
        $data = ChartData::fromSession($session);

        if (! $data) {
            abort(404, 'Raw data not found');
        }

        return response($data, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function routeData(TrainingSession $session)
    {
        $this->authorize('view', $session);
        $data = MapData::fromSession($session);

        if (! $data) {
            abort(404, 'Raw data not found');
        }

        return response($data, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
