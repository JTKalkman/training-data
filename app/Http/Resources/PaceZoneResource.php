<?php

namespace App\Http\Resources;

use App\Support\Calculators\PaceCalculator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaceZoneResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'zoneNumber' => $this->zone_number,
            'name'       => $this->name,
            'minSeconds' => $this->min_seconds_per_km,
            'minLabel'   => PaceCalculator::fromSeconds($this->min_seconds_per_km),
            'maxSeconds' => $this->max_seconds_per_km,
            'maxLabel'   => PaceCalculator::fromSeconds($this->max_seconds_per_km),
        ];
    }
}
