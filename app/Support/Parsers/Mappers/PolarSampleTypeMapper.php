<?php

namespace App\Support\Parsers\Mappers;

class PolarSampleTypeMapper
{
    protected const MAP = [
        0 => 'heart_rate',
        1 => 'speed',
        2 => 'cadence',
        3 => 'altitude',
        9 => 'temperature',
        10 => 'distance',
    ];

    public static function map(string $external): ?string
    {
        return self::MAP[$external] ?? null;
    }
}
