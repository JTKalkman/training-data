<?php

namespace App\Http\Resources;

use Carbon\CarbonInterval;
use Illuminate\Http\Request;
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
            'duration_human' => CarbonInterval::seconds($this->duration_seconds)->cascade()->forHumans(),

            'detail_url' => route('sessions.session', [
                'session' => $this->id,
            ]),

            'detail_url' => route('sessions.session', [
                'session' => $this->id,
            ]),

            'week_url' => route('sessions.week', [
                'year' => $this->started_at->isoWeekYear,
                'week' => $this->started_at->isoWeek,
            ]),

            'sport_type' => [
                'id' => $this->sportType->id,
                'name' => $this->sportType->name,
                'label' => $this->sportType->label,
            ],

            'training_summary' => $this->whenLoaded('trainingSummary', function () {
                return [
                    'min_heart_rate' => $this->trainingSummary?->min_heart_rate,
                    'avg_heart_rate' => $this->trainingSummary?->avg_heart_rate,
                    'max_heart_rate' => $this->trainingSummary?->max_heart_rate,
                ];
            }),

            'heart_rate_zones' => $this->whenLoaded('heartRateZones', function() {
                return $this->heartRateZones->map(function ($zone) {
                    return [
                        'id' => $zone->id,
                        'color' => $zone->color,
                        'zone_number' => $zone->zone_number,
                        'name' => $zone->name,
                        'min_bpm' => $zone->min_bpm,
                        'max_bpm' => $zone->max_bpm,
                    ];
                });
            }),
        ];
    }
}
