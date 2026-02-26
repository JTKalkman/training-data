<?php

namespace App\Support\Parsers\Mappers;

class HeartRateZoneMapper
{
    public const FIVE_ZONE_SYSTEM = [
        1 => [
            'color' => 'blue',
            'name' => 'Very light',
        ],
        2 => [
            'color' => 'green',
            'name' => 'Light',
        ],
        3 => [
            'color' => 'yellow',
            'name' => 'Moderate',
        ],
        4 => [
            'color' => 'orange',
            'name' => 'Hard',
        ],
        5 => [
            'color' => 'red',
            'name' => 'Maximum',
        ],
    ];

    public const FOUR_ZONE_SYSTEM = [
        2 => [
            'color' => 'green',
            'name' => 'Light',
        ],
        3 => [
            'color' => 'yellow',
            'name' => 'Moderate',
        ],
        4 => [
            'color' => 'orange',
            'name' => 'Hard',
        ],
        5 => [
            'color' => 'red',
            'name' => 'Maximum',
        ],
    ];

    public const THREE_ZONE_SYSTEM = [
        1 => [
            'color' => 'green',
            'name' => 'Light',
        ],
        2 => [
            'color' => 'yellow',
            'name' => 'Moderate',
        ],
        3 => [
            'color' => 'red',
            'name' => 'Maximum',
        ],
    ];

    public static function forZoneCount(int $zoneCount): array
    {
        return match ($zoneCount) {
            5 => self::FIVE_ZONE_SYSTEM,
            4 => self::FOUR_ZONE_SYSTEM,
            3 => self::THREE_ZONE_SYSTEM,
            default => [],
        };
    }
}
