<?php

namespace App\Http\Resources;

use App\Support\Calculators\PaceCalculator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaceZoneSetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'masSecondsPerKm' => $this->mas_seconds_per_km,
            'masPace' => PaceCalculator::fromSeconds($this->mas_seconds_per_km),
            'source' => $this->source,
            'scheme' => $this->scheme,
            'zones' => PaceZoneResource::collection($this->whenLoaded('paceZones')),
        ];
    }
}
