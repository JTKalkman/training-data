<?php

namespace App\Support;

use Carbon\CarbonInterval;

class Duration
{
    /**
     * Convert a string in "H:i:s" or "i:s" format to total seconds.
     */
    public static function fromHms(string $time): int
    {
        $ci = CarbonInterval::createFromFormat('H:i:s', $time);

        return ($ci->hours * 3600) + ($ci->minutes * 60) + $ci->seconds;
    }

    /**
     * Format seconds to "mm:ss" or "hh:mm:ss" for charts.
     */
    public static function clock(int $seconds): string
    {
        return gmdate($seconds >= 3600 ? 'H:i:s' : 'i:s', $seconds);
    }

    /**
     * Format seconds in human-readable form like "1h 2m".
     */
    public static function human(int $seconds): string
    {
        return CarbonInterval::seconds($seconds)
            ->cascade()
            ->forHumans([
                'short' => true,
                'parts' => 3,
            ]);
    }
}
