<?php

namespace App\Observers;

use App\Models\PaceZoneSet;
use App\Models\PhysicalData;

class PhysicalDataObserver
{
    /**
     * Handle the PhysicalData "saving" event.
     */
    public function saving(PhysicalData $physicalData): void
    {
        if (! $physicalData->isDirty('mas_seconds_per_km')) return;

        // Prevent overriding the user's own zones.
        if ($physicalData->activePaceZoneSet?->source === 'custom') return;

        /**
         * Unset the active pace if there is no MAS to base a pace zone set on.
         * Otherwise generate a new default set.
         */
        $physicalData->active_pace_zone_set_id = $physicalData->mas_seconds_per_km === null
            ? null
            : PaceZoneSet::forMas($physicalData->user, $physicalData->mas_seconds_per_km)->id;
    }
}
