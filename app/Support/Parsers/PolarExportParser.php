<?php

namespace App\Support\Parsers;

use App\Models\DataSource;
use App\Models\ExternalSportTypeMapping;
use App\Support\Calculators\PaceCalculator;
use App\Support\Duration;
use App\Support\Parsers\Mappers\HeartRateZoneMapper;
use App\Support\Parsers\Mappers\PolarExportSampleTypeMapper;
use Carbon\Carbon;

class PolarExportParser implements ParserInterface
{
    protected function isRunning(array $data): bool
    {
        return ExternalSportTypeMapping::where([
            'external_id' => $data['exercises'][0]["sport"]["id"] ?? null,
            'external_name' => 'RUNNING'
        ])->exists();
    }

    public function createDeviceData(array $data): ParsedDeviceData
    {
        return new ParsedDeviceData([
            'external_id' => $data['deviceId'] ?? null,
            'name' => $data['product']['modelName'] ?? null
        ]);
    }

    public function createSessionData(array $data): ParsedSessionData
    {
        $dataSource = DataSource::where(['name' => 'polar'])->first();
        $startedAt = Carbon::parse($data['exercises'][0]['startTime']);
        $UtcOffset = $data['exercises'][0]['timezoneOffsetMinutes'];
        $duration = Duration::fromMillis($data['exercises'][0]['durationMillis']);

        $externalSportType = ExternalSportTypeMapping::where('data_source_id', $dataSource->id)
            ->where('external_id', $data['exercises'][0]['sport']['id'] ?? null)
            ->firstOrFail();

        $sportType = $externalSportType->sportType;

        return new ParsedSessionData([
            'sport_type_id' => $sportType ? $sportType->id : null,
            'started_at' => $startedAt,
            'duration_seconds' => $duration,
            'utc_offset' => $UtcOffset,
            'data_source_id' => $dataSource->id,
            'external_id' => $data['exercises'][0]['identifier']['id']
        ]);
    }

    public function createSummaryData(array $data): ParsedSummaryData
    {
        $heartRateStatistics = null;
        foreach ($data['exercises'][0]['statistics']['statistics'] as $statistic) {
            if ($statistic['type'] === 'STATISTICS_TYPE_HEART_RATE') {
                $heartRateStatistics = $statistic;
            }
        }

        $minHeartRate = $heartRateStatistics['min'] ?? null;
        $avgHeartRate = $heartRateStatistics['avg'] ?? null;
        $maxHeartRate = $heartRateStatistics['max'] ?? null;

        return new ParsedSummaryData([
            'min_heart_rate' => $minHeartRate,
            'avg_heart_rate' => $avgHeartRate,
            'max_heart_rate' => $maxHeartRate,
            'distance' => $data['exercises'][0]['distanceMeters'] ?? null,
            'calories' => $data['exercises'][0]['calories'] ?? null,
            'has_route' => ! empty($data['exercises'][0]['routes']),
            'training_load' => [
                'training_load' => null,
                'training_load_pro' => null,
            ],
        ]);
    }

    public function createHeartRateZones(array $data): array
    {
        $heartRateZones = [];
        
        if (is_array($data['exercises'][0]['zones'])) {
            foreach ($data['exercises'][0]['zones'] as $zones) {
                if ($zones['type'] === 'ZONE_TYPE_HEART_RATE') {
                    $zoneSystem = HeartRateZoneMapper::forZoneCount(count($zones['zones']));

                    $mapped = array_map(function ($zone) {
                        return [
                            'lowerLimit' => $zone['lowerLimit'] ?? null,
                            'upperLimit' => $zone['higherLimit'] ?? null,
                            'inZone' => $zone['inZone'] ?? null
                        ];
                    }, $zones['zones']);

                    usort($mapped, fn ($a, $b) => $b['lowerLimit'] < $a['lowerLimit']);

                    foreach ($mapped as $zoneNumber => $heartRateZone) {
                        $name = $zoneSystem[$zoneNumber + 1]['name'] ?? null;
                        $color = $zoneSystem[$zoneNumber + 1]['color'] ?? null;

                        $heartRateZones[] = new ParsedHeartRateZoneData([
                            'zone_number' => (int) $zoneNumber,
                            'name' => (string) $name,
                            'min_bpm' => (int) $heartRateZone['lowerLimit'],
                            'max_bpm' => (int) $heartRateZone['upperLimit'],
                            'color' => (string) $color,
                            'in_zone_seconds' => (int) round($heartRateZone['inZone'] / 1000)
                        ]);
                    }
                }
            }
        }
        
        return $heartRateZones;
    }

    public function createSampleData(array $data): ParsedSampleData
    {
        $sampleData = [
            'sample_rate' => PHP_INT_MAX,
        ];

        if (isset($data['exercises'][0]['samples']['samples'][0])) {
            foreach ($data['exercises'][0]['samples']['samples'] as $samples) {
                if (PolarExportSampleTypeMapper::map($samples['type'])) {
                    $sampleData['sample_rate'] = min(
                        $sampleData['sample_rate'],
                        (int) (round($data['exercises'][0]['samples']['samples'][0]['intervalMillis'] / 1000))
                    );
                    
                    $sampleData[PolarExportSampleTypeMapper::map($samples['type'])] = $samples['values'];
                }
            }
        }

        if ($sampleData['sample_rate'] === PHP_INT_MAX) {
            $sampleData['sample_rate'] = 0; // TODO: Maybe handle null values in the future?
        }

        return new ParsedSampleData($sampleData);
    }

    public function createRouteData(array $data): ParsedRouteData
    {
        if (empty($data['exercises'][0]['routes']) || ! is_array($data['exercises'][0]['routes'])) {
            return new ParsedRouteData([]);
        }

        $parsedRoute = array_map(function ($dataPoint) {
            $time = null;
            $timeLabel = null;
            if (isset($dataPoint['elapsedMillis'])) {
                $time = Duration::fromMillis($dataPoint['elapsedMillis']);
                $timeLabel = Duration::human($time);
            }

            $altitude = null;
            if (isset($dataPoint['altitude'])) {
                $altitude = (float) $dataPoint['altitude'];
            }

            return [
                'lat' => (float) $dataPoint['latitude'],
                'lng' => (float) $dataPoint['longitude'],
                'alt' => $altitude,
                'time' => $time,
                'time_label' => $timeLabel,
            ];
        }, $data['exercises'][0]['routes']['route']['wayPoints']);

        return new ParsedRouteData($parsedRoute);
    }

    protected function calculatePace(array $speedData): array
    {
        return PaceCalculator::fromSpeeds($speedData);
    }

    public function parse(iterable $data): ParsedSession
    {
        $isRunning = $this::isRunning($data);

        $deviceData = $this->createDeviceData($data);
        $sessionData = $this->createSessionData($data);

        $summaryData = $this->createSummaryData($data);

        $heartRateZones = $this->createHeartRateZones($data);
        $sampleData = $this->createSampleData($data);

        if ($isRunning && $sampleData->speed && is_array($sampleData->speed)) {
            $paceData = $this->calculatePace($sampleData->speed);
            $sampleData->addPace($paceData);
    
            $summaryData->minPace = min($paceData);
            $summaryData->maxPace = max($paceData);
            $summaryData->avgPace = (int) round(array_sum($paceData) / count($paceData));
        }
    
        $routeData = $this->createRouteData($data);
    
        return new ParsedSession($deviceData, $sessionData, $summaryData, $heartRateZones, $sampleData, $routeData);
    }
}
