<?php

namespace App\Concerns;

use Illuminate\Validation\Rule;

trait PhysicalDataValidationRules
{
    protected const validHeartRate = ['nullable', 'integer', 'min:50', 'max:300'];

    /**
     * Get the validation rules used to validate user training settings..
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function physicalDataRules(): array
    {
        return [
            'date_of_birth'           => ['nullable', 'date'],
            'weight_kg'               => ['nullable', 'decimal:0,1', 'min:0', 'max:1000'],
            'height_cm'               => ['nullable', 'integer', 'min:0', 'max:300'],
            'sex'                     => ['nullable', 'string', Rule::in(['male', 'female', 'prefer_not_to_say']),],
            'resting_heart_rate'      => [...self::validHeartRate, 'required_with:max_heart_rate', 'lt:max_heart_rate'],
            'max_heart_rate'          => [...self::validHeartRate, 'required_with:resting_heart_rate', 'gt:resting_heart_rate'],
            'aerobic_threshold_bpm'   => [...self::validHeartRate, 'required_with:aerobic_threshold_bpm', 'gt:resting_heart_rate', 'lt:anaerobic_threshold_bpm'],
            'anaerobic_threshold_bpm' => [...self::validHeartRate, 'required_with:aerobic_threshold_bpm', 'gt:aerobic_threshold_bpm', 'lt:max_heart_rate'],
            'vo2_max'                 => ['nullable', 'integer', 'min:0', 'max:300'],
            'mas_pace'                => ['nullable', 'regex:/^\d+:[0-5]\d$/'],
            'mas_seconds_per_km'      => ['nullable', 'integer', 'min:0', 'max:1200'],
            'ftp_watts'               => ['nullable', 'integer', 'min:1', 'max:65535', 'lt:map_watts'],
            'map_watts'               => ['nullable', 'integer', 'min:1', 'max:65535', 'gt:ftp_watts']
        ];
    }
}
