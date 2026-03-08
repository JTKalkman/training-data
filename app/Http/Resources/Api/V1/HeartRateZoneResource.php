<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HeartRateZoneResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'zoneNumber' => $this->zone_number,
            'name' => $this->name,
            'minBpm' => $this->min_bpm,
            'maxBpm' => $this->max_bpm,
            'color' => $this->color,
            'inZoneSeconds' => $this->in_zone_seconds,
        ];
    }
}
