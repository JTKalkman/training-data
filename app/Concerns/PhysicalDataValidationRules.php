<?php

namespace App\Concerns;

use Illuminate\Validation\Rule;

trait PhysicalDataValidationRules
{
    protected const validHeartRate = ['nullable', 'integer', 'min:50', 'max:300'];

    protected const heartRateChain = [
        'resting_heart_rate',
        'aerobic_threshold_bpm',
        'anaerobic_threshold_bpm',
        'max_heart_rate',
    ];

    protected function heartRateRules(): array
    {
        $rules = [];

        foreach (self::heartRateChain as $i => $field) {
            $others = array_diff(self::heartRateChain, [$field]);

            $rules[$field] = [
                ...self::validHeartRate,
                'required_with:' . implode(',', $others),
            ];

            if (isset(self::heartRateChain[$i + 1])) {
                $rules[$field][] = 'lt:' . self::heartRateChain[$i + 1];
            }
        }

        return $rules;
    }

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
            ...$this->heartRateRules(),
            'vo2_max'                 => ['nullable', 'integer', 'min:0', 'max:300'],
            'mas_pace'                => ['nullable', 'regex:/^\d+:[0-5]\d$/'],
            'mas_seconds_per_km'      => ['nullable', 'integer', 'min:0', 'max:1200'],
            'ftp_watts'               => ['nullable', 'required_with:map_watts', 'integer', 'min:1', 'max:65535', 'lt:map_watts'],
            'map_watts'               => ['nullable', 'required_with:ftp_watts', 'integer', 'min:1', 'max:65535', 'gt:ftp_watts']
        ];
    }
}
