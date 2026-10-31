<?php

namespace App\Support\Services;

class PaceZoneGenerator
{
    /**
     * Pace in sec/km at a given percentage of the anchor's speed. 
     * Higher percentage = faster = fewer seconds.
     */
    private function paceAtPercentOfSpeed(int $anchorSecondsPerKm, float $percent): int
    {
        return (int) round($anchorSecondsPerKm * 100 / $percent);
    }

    public function generateDefaultPaceZones(array $scheme, int $anchorSecondsPerKm): array
    {
        $zones = [];

        foreach ($scheme['zones'] as $zoneNumber => $zone) {
            $zones[] = [
                'zone_number' => $zoneNumber,
                'name' => $zone['name'],
                'min_seconds_per_km' => $this->paceAtPercentOfSpeed($anchorSecondsPerKm, $zone['to_pct']),   // fast end
                'max_seconds_per_km' => $this->paceAtPercentOfSpeed($anchorSecondsPerKm, $zone['from_pct']), // slow end
            ];
        }

        return $zones;
    }
}
