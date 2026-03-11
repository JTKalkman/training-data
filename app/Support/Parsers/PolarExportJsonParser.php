<?php

namespace App\Support\Parsers;

use App\Models\DataSource;
use App\Models\Device;
use App\Support\Duration;
use App\Support\Parsers\Mappers\HeartRateZoneMapper;
use App\Support\Parsers\Mappers\SportTypeMapper;
use Carbon\Carbon;

class PolarExportJsonParser implements ParserInterface
{
    public function createDeviceData($data): ParsedDeviceData
    {
        $deviceId = $data['deviceId'] ?? null;

        $device = Device::whereHas('dataSource', fn($q) => $q->where('name', 'polar'))
            ->where('external_id', $deviceId)
            ->first();

        // Devices can be missing.
        return new ParsedDeviceData([
            'external_id' => $device ? $device->external_id : null,
            'name' => $device ? $device->name : null,
        ]);
    }

    public function createSessionData(array $data): ParsedSessionData
    {
        $sportType = SportTypeMapper::map($data['sport']);
        $startedAt = Carbon::parse($data['startTime']);
        $UtcOffset = $data['timezoneOffset'];
        $duration = Duration::fromIso($data['duration']);
        $dataSource = DataSource::where(['name' => 'polar'])->first();

        return new ParsedSessionData([
            'sport_type_id' => $sportType ? $sportType->id : null,
            'started_at' => $startedAt,
            'duration_seconds' => $duration,
            'utc_offset' => $UtcOffset,
            'data_source_id' => $dataSource->id,
            'external_id' => $data['externalId'],
        ]);
    }

    public function createSummaryData(array $data): ParsedSummaryData
    {
        return new ParsedSummaryData([
            'min_heart_rate' => $data['minHeartRate'],
            'avg_heart_rate' => $data['avgHeartRate'],
            'max_heart_rate' => $data['maxHeartRate'],
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
            $zoneNumber = isset($heartRateZone['zoneIndex']) ? ((int) $heartRateZone['zoneIndex']) : null;
            $name = $zoneSystem[$zoneNumber]['name'] ?? null;
            $minBpm = isset($heartRateZone['lowerLimit']) ? ((int) $heartRateZone['lowerLimit']) : null;
            $maxBpm = isset($heartRateZone['higherLimit']) ? ((int) $heartRateZone['higherLimit']) : null;
            $color = $zoneSystem[$zoneNumber]['color'] ?? null;
            $inZoneSeconds = isset($heartRateZone['inZone']) ? Duration::fromIso($heartRateZone['inZone']) : null;

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
        $sampleData = [];

        if (count($data['samples']['heart_rate']) > 0) {
            $sampleData['heart_rate'] = array_map(fn($sample) => $sample['value'] ?? null, $data['samples']['heart_rate']);
        }
            
        if (count($data['samples']['altitude']) > 0) {
            $sampleData['altitude'] = array_map(fn($sample) => $sample['value'] ?? null, $data['samples']['altitude']);    
        }

        if (count($data['samples']['speed']) > 0) {
            $sampleData['speed'] = array_map(fn($sample) => $sample['value'] ?? null, $data['samples']['speed']);
        }
            
        if (count($data['samples']['cadence']) > 0) {
            $sampleData['cadence'] = array_map(fn($sample) => $sample['value'] ?? null, $data['samples']['cadence']);
        }
            
        if (count($data['samples']['distance']) > 0) {
            $sampleData['distance'] = array_map(fn($sample) => $sample['value'] ?? null, $data['samples']['distance']);
        }
            
        if (count($data['samples']['temperature']) > 0) {
            $sampleData['temperature'] = array_map(fn($sample) => $sample['value'] ?? null, $data['samples']['temperature']);    
        }

        if (count($data['samples']['pace']) > 0) {
            $sampleData['pace'] = array_map(fn($sample) => $sample['value'] ?? null, $data['samples']['pace']);
        }

        $sampleData['sample_rate'] = 1;

        return new ParsedSampleData($sampleData);
    }

    public function createRouteData(array $data): ParsedRouteData
    {
        if (! $data['has_route'] || ! $data['route'] || ! is_array($data['route'])) {
            return new ParsedRouteData([]);
        }

        $parsedRoute = array_map(function ($dataPoint, $index) {
            $time = $index; // The sample rate is one second.

            return [
                'lat' => (float) $dataPoint['latitude'],
                'lng' => (float) $dataPoint['longitude'],
                'time' => $time,
                'time_label' => Duration::human($time),
            ];
        }, $data['route'], array_keys($data['route']));

        return new ParsedRouteData($parsedRoute);
    }

    protected function calculatePace(array $speedData): array
    {
        $paces = array_map(function ($speed) {
            $speed = (float) $speed;

            return $speed > 0 ? round(1000 / $speed) : 0;
        }, $speedData);

        return $paces;
    }

    public function parse(iterable $data): ParsedSession
    {
        $deviceData = $this->createDeviceData($data);
        $sessionData = $this->createSessionData($data);
        $summaryData = $this->createSummaryData($data);
        $heartRateZones = $this->createHeartRateZones($data);
        $sampleData = $this->createSampleData($data);

        $isRunning = SportTypeMapper::map($data['sport'] ?? '')?->name === 'running';

        if ($isRunning && $sampleData->speed && is_array($sampleData->speed)) {
            $sampleData->addPace($this->calculatePace($sampleData->speed));
        }

        $routeData = $this->createRouteData($data);

        return new ParsedSession($deviceData, $sessionData, $summaryData, $heartRateZones, $sampleData, $routeData);
    }
}
