<?php

namespace App\Http\Resources;

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
            'duration' => $this->duration,

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
        ];
    }
}
