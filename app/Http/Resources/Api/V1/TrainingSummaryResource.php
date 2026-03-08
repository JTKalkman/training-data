<?php

namespace App\Http\Resources\Api\V1;

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
            'id' => $this->id,
            'minHeartRate' => $this->min_heart_rate,
            'avgHeartRate' => $this->avg_heart_rate,
            'maxHeartRate' => $this->max_heart_rate,
            'distanceMeters' => $this->distance,
            'calories' => $this->calories,
            'hasRoute' => (bool) $this->has_route,
        ];
    }
}
