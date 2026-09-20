<?php

namespace App\Concerns;

trait TrainingSettingsValidationRules
{
    protected const validHeartRate = ['nullable', 'integer', 'min:50', 'max:300'];

    /**
     * Get the validation rules used to validate user training settings..
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function trainingSettingsRules(): array
    {
        return [
            'date_of_birth'           => ['nullable', 'date'],
            'weight_kg'               => ['nullable', 'decimal:1', 'min:0', 'max:1000'],
            'height_cm'               => ['nullable', 'integer', 'min:50', 'max:300'],
            'resting_heart_rate'      => [...self::validHeartRate, 'lt:max_heart_rate'],
            'max_heart_rate'          => [...self::validHeartRate, 'gt:resting_heart_rate'],
            'aerobic_threshold_bpm'   => [...self::validHeartRate, 'lt:anaerobic_threshold_bpm'],
            'anaerobic_threshold_bpm' => [...self::validHeartRate, 'gt:aerobic_threshold_bpm'],
            'mas_pace'                => ['nullable', 'regex:/^\d+:[0-5]\d$/'],
            'mas_seconds_per_km'      => ['nullable', 'integer', 'min:0', 'max:1200'],
            'ftp_watts'               => ['nullable', 'integer', 'min:1', 'max:65535', 'lt:map_watts'],
            'map_watts'               => ['nullable', 'integer', 'min:1', 'max:65535', 'gt:ftp_watts']
        ];
    }
}
