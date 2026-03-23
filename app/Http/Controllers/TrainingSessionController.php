<?php

namespace App\Http\Controllers;

use App\Http\Resources\TrainingSessionResource;
use App\Models\TrainingSession;
use App\Support\ChartData;
use App\Support\MapData;
use Inertia\Inertia;

class TrainingSessionController extends Controller
{
    private function renderTrainingSession(TrainingSession $session)
    {
        $session->load(['sportType', 'trainingSummary', 'heartRateZones']);
        $previousSession = $session->previousSession();
        $nextSession = $session->nextSession();

        return Inertia::render('TrainingSession', [
            'trainingSession' => new TrainingSessionResource($session),
            'navigation' => [
                'prev' => [
                    'id' => $previousSession?->id,
                    'url' => $previousSession ? route('training-sessions.show', $previousSession) : null,
                ],
                'next' => [
                    'id' => $nextSession?->id,
                    'url' => $nextSession ? route('training-sessions.show', $nextSession) : null,
                ],
            ],
        ]);
    }

    public function show(TrainingSession $session)
    {
        $this->authorize('view', $session);

        return $this->renderTrainingSession($session);
    }

    public function update(TrainingSession $session)
    {
        $this->authorize('update', $session);
        $data = request()->validate([
            'rating' => 'nullable|integer|min:1|max:5',
            'notes' => ['nullable', 'string', 'max:1000', function($attr, $value, $fail) {
                if ($value !== strip_tags($value)) {
                    $fail('HTML is not allowed.');
                }
            }],
        ]);
        $session->update($data);

        return $this->renderTrainingSession($session);
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
