<?php

namespace App\Support;

use Carbon\Carbon;

class PolarTime {
    public static function toUtc(string $localTime, int $offsetMinutes): Carbon
    {
        return Carbon::parse($localTime, 'UTC')->subMinutes($offsetMinutes);
    }
}
