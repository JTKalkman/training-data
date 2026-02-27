<?php

namespace App\Support\Parsers;

use App\Models\DataSource;
use App\Support\Duration;
use App\Support\Parsers\Mappers\HeartRateZoneMapper;
use App\Support\Parsers\Mappers\PolarSampleTypeMapper;
use App\Support\Parsers\Mappers\SportTypeMapper;
use Carbon\Carbon;

class PolarJsonParser implements ParserInterface
{
    public function createDeviceData($data): ParsedDeviceData
    {
        return new ParsedDeviceData([
            'external_id' => $data['device_id'],
            'name' => $data['device'],
        ]);
    }

    public function createSessionData(array $data): ParsedSessionData
    {
        $sportType = SportTypeMapper::map($data['detailed_sport_info']);
        $startedAt = Carbon::parse($data['start_time']);
        $UtcOffset = $data['start_time_utc_offset'];
        $duration = Duration::fromIso($data['duration']);
        $dataSource = DataSource::where(['name' => 'polar'])->first();

        return new ParsedSessionData([
            'sport_type_id' => $sportType ? $sportType->id : null,
            'started_at' => $startedAt,
            'duration_seconds' => $duration,
            'utc_offset' => $UtcOffset,
            'data_source_id' => $dataSource->id,
            'external_id' => $data['id'],
        ]);
    }

    public function createSummaryData(array $data): ParsedSummaryData
    {
        $avgHeartRate = $data['heart_rate']['average'] ?? null;
        $maxHeartRate = $data['heart_rate']['maximum'] ?? null;
        $minHeartRate = PHP_INT_MAX;

        // Might not be fully memory safe. Fine for now.
        foreach ($data['samples'] as $samples) {
            if ($samples['sample_type'] === 0) { // Heart rate samples.
                foreach (explode(',', $samples['data']) as $value) {
                    $val = (int) $value;
                    if ($val > 0 && $val < $minHeartRate) {
                        $minHeartRate = $val;
                    }
                }
            }
        }

        if ($minHeartRate === PHP_INT_MAX) {
            $minHeartRate = null;
        }

        return new ParsedSummaryData([
            'min_heart_rate' => $minHeartRate,
            'avg_heart_rate' => $avgHeartRate,
            'max_heart_rate' => $maxHeartRate,
            'distance' => $data['distance'] ?? null,
            'calories' => $data['calories'] ?? null,
            'has_route' => $data['has_route'] ?? false,
            'training_load' => [
                'training_load' => $data['training_load'] ?? null,
                'training_load_pro' => $data['training_load_pro'] ?? null,
            ],
        ]);
    }

    public function createHeartRateZones(array $data): array
    {
        $HeartRateZones = [];

        if (! isset($data['heart_rate_zones']) || ! is_array($data['heart_rate_zones'])) {
            return $HeartRateZones;
        }

        $zoneSystem = HeartRateZoneMapper::forZoneCount(count($data['heart_rate_zones']));

        foreach ($data['heart_rate_zones'] as $heartRateZone) {
            $zoneNumber = isset($heartRateZone['index']) ? ((int) $heartRateZone['index']) + 1 : null;
            $name = $zoneSystem[$zoneNumber]['name'] ?? null;
            $minBpm = isset($heartRateZone['lower_limit']) ? ((int) $heartRateZone['lower_limit']) : null;
            $maxBpm = isset($heartRateZone['upper_limit']) ? ((int) $heartRateZone['upper_limit']) : null;
            $color = $zoneSystem[$zoneNumber]['color'] ?? null;
            $inZoneSeconds = isset($heartRateZone['in_zone']) ? Duration::fromIso($heartRateZone['in_zone']) : null;

            $HeartRateZones[] = new ParsedHeartRateZoneData([
                'zone_number' => $zoneNumber,
                'name' => $name,
                'min_bpm' => $minBpm,
                'max_bpm' => $maxBpm,
                'color' => $color,
                'in_zone_seconds' => $inZoneSeconds,
            ]);
        }

        return $HeartRateZones;
    }

    public function createSampleData(array $data): ParsedSampleData
    {
        $sampleRates = [];
        $sampleData = [
            'sample_rate' => null,
        ];

        if (isset($data['samples']) && is_array($data['samples'])) {
            foreach ($data['samples'] as $samples) {
                if (PolarSampleTypeMapper::map($samples['sample_type'])) {
                    $sampleRates[] = $samples['recording_rate'];
                    $sampleData[PolarSampleTypeMapper::map($samples['sample_type'])] = $samples['data'];
                }
            }
        }

        $sampleData['sample_rate'] = min($sampleRates);

        return new ParsedSampleData($sampleData);
    }

    public function createRouteData(array $data): ParsedRouteData
    {
        if (! $data['has_route'] || ! $data['route'] || ! is_array($data['route'])) {
            return new ParsedRouteData([]);
        }

        $parsedRoute = array_map(function ($dataPoint) {
            $time = Duration::fromIso($dataPoint['time']);

            return [
                'lat' => (float) $dataPoint['latitude'],
                'lng' => (float) $dataPoint['longitude'],
                'time' => $time,
                'time_label' => Duration::human($time),
            ];
        }, $data['route']);

        return new ParsedRouteData($parsedRoute);
    }

    public function parse(iterable $data): ParsedSession
    {
        $deviceData = $this->createDeviceData($data);
        $sessionData = $this->createSessionData($data);
        $summaryData = $this->createSummaryData($data);
        $heartRateZones = $this->createHeartRateZones($data);
        $sampleData = $this->createSampleData($data);
        $routeData = $this->createRouteData($data);

        return new ParsedSession($deviceData, $sessionData, $summaryData, $heartRateZones, $sampleData, $routeData);
    }
}
