<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class PhysicalDataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'dateOfBirth'           => $this->date_of_birth?->format('Y-m-d'),
            'sex'                   => $this->sex,
            'weightKg'              => $this->weight_kg,
            'heightCm'              => $this->height_cm,
            'restingHeartRate'      => $this->resting_heart_rate,
            'maxHeartRate'          => $this->max_heart_rate,
            'aerobicThresholdBpm'   => $this->aerobic_threshold_bpm,
            'anaerobicThresholdBpm' => $this->anaerobic_threshold_bpm,
            'vo2Max'                => $this->vo2_max,
            'masPace'               => $this->mas_pace,
            'mapWatts'              => $this->map_watts,
            'ftpWatts'              => $this->ftp_watts,
        ];
    }
}
