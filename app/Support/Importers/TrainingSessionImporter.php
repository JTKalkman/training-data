<?php

namespace App\Support\Importers;

use App\Models\DataSource;
use App\Models\Device;
use App\Models\HeartRateZone;
use App\Models\TrainingSession;
use App\Models\User;
use App\Support\Duration;
use App\Support\Parsers\ParsedSession;

class TrainingSessionImporter
{
    private function cast(string $type, string $value): int|float|null
    {
        return match ($type) {
            'int' => (int) $value,
            'float' => (float) $value,
            default => $value,
        };
    }

    private function parseSamples(string $type, string|array $data): ?array
    {
        $samples = $data;
        if (is_string($samples)) {
            $samples = explode(',', $samples);
        }

        if (count($samples) === 0) {
            return null;
        }

        $samples = array_map(fn ($sample) => $this->cast($type, $sample), $samples);

        if (array_sum($samples) == 0) {
            return null;
        } else {
            return $samples;
        }
    }

    public function import(User $user, DataSource $dataSource, ParsedSession $parsedSession): TrainingSession
    {
        $device = Device::firstOrCreate([
            'user_id' => $user->id,
            'data_source_id' => $dataSource->id,
            'external_id' => $parsedSession->deviceData->externalId,
        ], [
            'name' => $parsedSession->deviceData->name,
        ]);

        $trainingSession = TrainingSession::firstOrCreate([
            'user_id' => $user->id,
            'data_source_id' => $dataSource->id,
            'external_id' => $parsedSession->sessionData->externalId,
        ], [
            'sport_type_id' => $parsedSession->sessionData->sportTypeId,
            'started_at' => $parsedSession->sessionData->startedAt,
            'utc_offset' => $parsedSession->sessionData->UtcOffset,
            'duration_seconds' => $parsedSession->sessionData->durationSeconds,
            'data_source_id' => $parsedSession->sessionData->dataSourceId,
            'external_id' => $parsedSession->sessionData->externalId,
            'device_id' => $device->id,
        ]);

        foreach ($parsedSession->heartRateZones as $heartRateZone) {
            HeartRateZone::firstOrCreate([
                'training_session_id' => $trainingSession->id,
                'zone_number' => $heartRateZone->zoneNumber,
            ], [
                'name' => $heartRateZone->name,
                'min_bpm' => $heartRateZone->minBpm,
                'max_bpm' => $heartRateZone->maxBpm,
                'color' => $heartRateZone->color,
                'in_zone_seconds' => $heartRateZone->inZoneSeconds,
            ]);
        }

        if ($parsedSession->sampleData->heartRate) {
            $dataStreamer = new DataStreamer($trainingSession->sampleDataPath());

            $sampleRate = $parsedSession->sampleData->sampleRate;

            $heartRateSamples = $parsedSession->sampleData->heartRate ? $this->parseSamples('int', $parsedSession->sampleData->heartRate) : null;
            $speedSamples = $parsedSession->sampleData->speed ? $this->parseSamples('float', $parsedSession->sampleData->speed) : null;
            $cadenceSamples = $parsedSession->sampleData->cadence ? $this->parseSamples('int', $parsedSession->sampleData->cadence) : null;
            $altitudeSamples = $parsedSession->sampleData->altitude ? $this->parseSamples('float', $parsedSession->sampleData->altitude) : null;
            $temperatureSamples = $parsedSession->sampleData->temperature ? $this->parseSamples('float', $parsedSession->sampleData->temperature) : null;
            $distanceSamples = $parsedSession->sampleData->distance ? $this->parseSamples('float', $parsedSession->sampleData->distance) : null;

            $sampleCount = count($heartRateSamples);
            $time = 0;

            for ($i = 0; $i < $sampleCount; $i++) {
                $dataPoint = [
                    'time' => $time,
                    'time_label' => Duration::clock($time),
                    'heart_rate' => $heartRateSamples[$i],
                ];

                if ($speedSamples) {
                    $dataPoint['speed'] = $speedSamples[$i] ?? null;
                }

                if ($cadenceSamples) {
                    $dataPoint['cadence'] = $cadenceSamples[$i] ?? null;
                }

                if ($altitudeSamples) {
                    $dataPoint['altitude'] = $altitudeSamples[$i] ?? null;
                }

                if ($temperatureSamples) {
                    $dataPoint['temperature'] = $temperatureSamples[$i] ?? null;
                }

                if ($distanceSamples) {
                    $dataPoint['distance'] = $distanceSamples[$i] ?? null;
                }

                $dataStreamer->write($dataPoint);
                $time += $sampleRate;
            }

            $dataStreamer->close();
        }

        if ($parsedSession->routeData->coordinates) {
            $dataStreamer = new DataStreamer($trainingSession->routePath());

            foreach ($parsedSession->routeData->coordinates as $coordinate) {
                $dataStreamer->write($coordinate);
            }

            $dataStreamer->close();
        }

        return $trainingSession;
    }
}
