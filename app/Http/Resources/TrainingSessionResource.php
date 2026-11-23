<?php

namespace App\Http\Resources;

use App\Support\Duration;
use Carbon\CarbonTimeZone;
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
        $local = $this->started_at->copy()
            ->setTimezone(CarbonTimeZone::createFromMinuteOffset($this->utc_offset));

        return [
            'id' => $this->id,
            'startedAt' => $local->toIso8601String(),
            'startedAtHuman' => $local->format('j F H:i'),
            'duration' => $this->duration_seconds,
            'durationHuman' => Duration::human($this->duration_seconds),
            'notes' => $this->notes,
            'rating' => $this->rating,

            'year' => $local->isoWeekYear,
            'week' => $local->isoWeek,

            'sportType' => new SportTypeResource($this->sportType),

            'trainingSummary' => $this->whenLoaded('trainingSummary',
                fn () => new TrainingSummaryResource($this->trainingSummary)
            ),

            'heartRateZones' => $this->whenLoaded('heartRateZones',
                fn () => HeartRateZoneResource::collection($this->heartRateZones)
            ),

            'paceZoneSet' => $this->whenLoaded('paceZoneSet',
                fn () => new PaceZoneSetResource($this->paceZoneSet)
            ),
        ];
    }
}
