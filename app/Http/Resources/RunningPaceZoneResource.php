<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RunningPaceZoneResource extends JsonResource
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
            'minSeconds' => $this->min_seconds,
            'maxSeconds' => $this->max_seconds,
            'color' => $this->color,
            'inZoneSeconds' => $this->in_zone_seconds,
        ];
    }
}
