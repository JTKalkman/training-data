<?php

namespace App\Support;

use Carbon\CarbonInterval;

class Duration
{
    public static function clock(int $seconds): string
    {
        return gmdate($seconds >= 3600 ? 'H:i:s' : 'i:s', $seconds);
    }

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
