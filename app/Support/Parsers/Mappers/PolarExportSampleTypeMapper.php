<?php

namespace App\Support\Parsers\Mappers;

class PolarExportSampleTypeMapper
{
    protected const MAP = [
        'HEART_RATE' => 'heart_rate',
        'SPEED' => 'speed',
        'CADENCE' => 'cadence',
        'ALTITUDE' => 'altitude',
        'TEMPERATURE' => 'temperature',
        'DISTANCE' => 'distance',
    ];

    public static function map(string $external): ?string
    {
        return self::MAP[$external] ?? null;
    }
}
