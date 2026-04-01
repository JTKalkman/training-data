<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'minHeartRate' => $this->min_heart_rate,
            'avgHeartRate' => $this->avg_heart_rate,
            'maxHeartRate' => $this->max_heart_rate,
            'minPace' => $this->min_pace_seconds,
            'avgPace' => $this->avg_pace_seconds,
            'maxPace' => $this->max_pace_seconds,
            'hasRoute' => $this->has_route,
            'distance' => $this->distance / 1000,
            'calories' => $this->calories,
        ];
    }
}
