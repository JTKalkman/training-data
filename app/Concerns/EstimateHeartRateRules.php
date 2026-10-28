<?php

namespace App\Concerns;

trait EstimateHeartRateRules
{
    /**
     * Get the validation rules used to validate the date of birth.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function dateOfBirthRules(): array
    {
        return ['required', 'date', 'before:today'];
    }
}
