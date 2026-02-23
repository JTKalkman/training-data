<?php

namespace App\Http\Resources;

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
            'id' => $this->id,
            'color' => $this->color,
            'zone_number' => $this->zone_number,
            'name' => $this->name,
            'min_bpm' => $this->min_bpm,
            'max_bpm' => $this->max_bpm,
        ];
    }
}
