<?php

namespace App\Support;

class HeartRateZoneColors
{
    public const FIVE_ZONE_SYSTEM = [
        1 => 'blue',
        2 => 'green',
        3 => 'yellow',
        4 => 'orange',
        5 => 'red',
    ];

    public const FOUR_ZONE_SYSTEM = [
        2 => 'green',
        3 => 'yellow',
        4 => 'orange',
        5 => 'red',
    ];

    public const THREE_ZONE_SYSTEM = [
        1 => 'green',
        2 => 'orange',
        3 => 'red',
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
