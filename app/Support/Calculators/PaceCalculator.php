<?php

namespace App\Support\Calculators;

class PaceCalculator
{
    /**
     * Fallback pace (seconds per km) used when a speed value is missing
     * or non-numeric.
     */
    protected const DEFAULT_PACE_SECONDS = 1200;

    /**
     * Slowest pace we'll report, in seconds per km (20:00/km). Anything
     * slower is clamped here rather than showing an unrealistic pace.
     */
    protected const MAX_PACE_SECONDS = 1200;

    /**
     * Fastest pace we'll report, in seconds per km (3:30/km). Anything
     * faster is clamped here, treated as a data/sensor glitch.
     */
    protected const MIN_PACE_SECONDS = 210;

    protected const SECONDS_PER_HOUR = 3600;

    /**
     * Converts an array of speed values (km/h) into paces (seconds per km),
     * clamped to a plausible range.
     *
     * @param  array<int, mixed>  $speedsKmh
     * @return array<int, int>
     */
    public function fromSpeeds(array $speedsKmh): array
    {
        return array_map(fn ($speed) => $this->fromSpeed($speed), $speedsKmh);
    }

    protected function fromSpeed(mixed $speedKmh): int
    {
        if (! is_numeric($speedKmh)) {
            return self::DEFAULT_PACE_SECONDS;
        }

        $speedKmh = (float) $speedKmh;

        if ($speedKmh <= 0) {
            return self::DEFAULT_PACE_SECONDS;
        }

        $pace = (int) round(self::SECONDS_PER_HOUR / $speedKmh);

        return min(max($pace, self::MIN_PACE_SECONDS), self::MAX_PACE_SECONDS);
    }
}
