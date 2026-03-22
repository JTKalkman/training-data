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
            'startedAt' => $this->started_at->toIso8601String(),
            'startedAtHuman' => $this->started_at->format('j F H:i'),
            'duration' => $this->duration_seconds,
            'durationHuman' => Duration::human($this->duration_seconds),
            'notes' => $this->notes, // TODO: maybe we should not return this by default?
            'rating' => $this->rating,

            'year' => $this->started_at->isoWeekYear,
            'week' => $this->started_at->isoWeek,

            'sportType' => new SportTypeResource($this->sportType),

            'trainingSummary' => $this->whenLoaded('trainingSummary',
                fn () => new TrainingSummaryResource($this->trainingSummary)
            ),

            'heartRateZones' => $this->whenLoaded('heartRateZones',
                fn () => HeartRateZoneResource::collection($this->heartRateZones)
            ),
        ];
    }
}
