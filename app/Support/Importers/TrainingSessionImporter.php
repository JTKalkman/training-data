<?php

namespace App\Support\Importers;

use App\Models\HeartRateZone;
use App\Models\TrainingSession;
use App\Models\TrainingSummary;
use App\Support\Parsers\ParsedSession;
use Illuminate\Support\Facades\File;

class TrainingSessionImporter
{
    public function import(ParsedSession $parsedSession, int $userId): TrainingSession
    {
        $trainingSession = TrainingSession::create([
            'user_id' => $userId,
            'sport_type_id' => $parsedSession->sessionData->sportType,
            'started_at' => $parsedSession->sessionData->startedAt->toString(),
            'duration_seconds' => $parsedSession->sessionData->durationSeconds,
            'source' => $parsedSession->sessionData->source,
        ]);

        $jsonPath = $trainingSession->filePath();

        if (! File::exists(dirname($jsonPath))) {
            File::makeDirectory(dirname($jsonPath), 0755, true);
        }
        File::put($jsonPath, json_encode($parsedSession->rawData));

        TrainingSummary::create([
            'training_session_id' => $trainingSession->id,
            'min_heart_rate' => $parsedSession->summary->minHeartRate,
            'avg_heart_rate' => $parsedSession->summary->avgHeartRate,
            'max_heart_rate' => $parsedSession->summary->maxHeartRate,
        ]);

        foreach ($parsedSession->heartRateZones as $zone) {
            HeartRateZone::create([
                'training_session_id' => $trainingSession->id,
                'zone_number' => $zone->zoneNumber,
                'name' => $zone->name,
                'min_bpm' => $zone->minBpm,
                'max_bpm' => $zone->maxBpm,
                'color' => $zone->color,
            ]);
        }

        return $trainingSession;
    }
}
