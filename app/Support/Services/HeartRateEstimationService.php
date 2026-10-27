<?php

namespace App\Support\Services;

class HeartRateEstimationService
{
    /**
     * Uses Tanaka's formula to determine the max heart rate 
     * and the aerobic thresholds.
     */
    public function estimate(int $age): array
    {
        $maxHeartRate = round(208 - (0.7 * $age));

        return [
            'max_heart_rate' => $maxHeartRate,
            'aerobic_threshold_bpm' => round($maxHeartRate * 0.75),
            'anaerobic_threshold_bpm' => round($maxHeartRate * 0.88)
        ];
    }
}
