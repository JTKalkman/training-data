<?php

namespace App\Http\Resources;

use App\Support\Duration;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingSessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'started_at' => $this->started_at->toIso8601String(),
            'started_at_human' => $this->started_at->format('D M d, Y H:i'),
            'duration' => $this->duration_seconds,
            'duration_human' => Duration::human($this->duration_seconds),

            'year' => $this->started_at->isoWeekYear,
            'week' => $this->started_at->isoWeek,

            'sport_type' => new SportTypeResource($this->sportType),

            'training_summary' => $this->whenLoaded('trainingSummary', 
                fn() => new TrainingSummaryResource($this->trainingSummary)
            ),

            'heart_rate_zones' => $this->whenLoaded('heartRateZones', 
                fn () => HeartRateZoneResource::collection($this->heartRateZones)
            ),
        ];
    }
}
