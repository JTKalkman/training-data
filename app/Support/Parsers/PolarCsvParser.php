<?php

namespace App\Support\Parsers;

use App\Support\Duration;
use App\Support\Parsers\Mappers\SportTypeMapper;
use Carbon\Carbon;

class PolarCsvParser implements ParserInterface
{
    public function createSessionData(array $headerData): ParsedSessionData
    {
        $sportType = SportTypeMapper::map($headerData['Sport']);
        $startedAt = Carbon::createFromFormat('d-m-Y H:i:s', $headerData['Date'].' '.$headerData['Start time']);
        $duration = Duration::fromHms($headerData['Duration']);

        return new ParsedSessionData([
            'sport_type' => $sportType ? $sportType->id : null,
            'started_at' => $startedAt,
            // TODO: UTC Offset.
            'duration_seconds' => $duration,
            'source' => 'polar', // TODO Update source here.
        ]);
    }

    public function createSummaryData(array $headerData): ParsedSummaryData
    {
        return new ParsedSummaryData([
            'min_heart_rate' => $headerData['HR sit'] ?? null,
            'avg_heart_rate' => $headerData['Average heart rate (bpm)'] ?? null,
            'max_heart_rate' => $headerData['HR max'] ?? null,
        ]);
    }

    public function createRawDataRecord(array $rawData): array
    {
        $time = Duration::fromHms($rawData['Time']);

        return [
            'time' => $time,
            'heart_rate' => $rawData['HR (bpm)'] ?? null,
        ];
    }

    public function createHeartRateZones(array $data): array
    {
        $fakeHeartRateZones = require database_path('seeders/sample-data/fake_heart_rate_zones.php');

        $HeartRateZones = array_map(function ($heartRateZone) {
            return new ParsedHeartRateZoneData([
                'zone_number' => $heartRateZone['zone_number'],
                'name' => $heartRateZone['name'],
                'min_bpm' => $heartRateZone['min_bpm'],
                'max_bpm' => $heartRateZone['max_bpm'],
                'color' => $heartRateZone['color'],
            ]);
        }, $fakeHeartRateZones);

        return $HeartRateZones;
    }

    public function parse(iterable $rows): ParsedSession
    {
        $rows = iterator_to_array($rows, false);

        $headers = $rows[0] ?? [];
        $headerValues = $rows[1] ?? [];
        $headerData = array_combine($headers, $headerValues);

        $sessionData = $this->createSessionData($headerData);
        $summaryData = $this->createSummaryData($headerData);
        $heartRateZones = $this->createHeartRateZones();

        $rawDataHeaders = $rows[2] ?? [];
        $rawDataRecords = [];
        foreach (array_slice($rows, 3) as $row) {
            $rawData = array_combine($rawDataHeaders, $row);
            $rawDataRecords[] = $this->createRawDataRecord($rawData);
        }

        return new ParsedSession($sessionData, $summaryData, $heartRateZones, $rawDataRecords);
    }
}
