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
            'min_heart_rate' => $this->min_heart_rate,
            'avg_heart_rate' => $this->avg_heart_rate,
            'max_heart_rate' => $this->max_heart_rate,
        ];
    }
}
