<?php

namespace App\Http\Requests\Settings;

use App\Concerns\PhysicalDataValidationRules;
use App\Support\Calculators\PaceCalculator;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PhysicalDataUpdateRequest extends FormRequest
{
    use PhysicalDataValidationRules;

    protected function prepareForValidation(): void
    {
        if ($this->has('mas_pace')) {
            $this->merge([
                'mas_seconds_per_km' => PaceCalculator::toSeconds((string) $this->input('mas_pace')),
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'mas_pace.regex' => 'Enter your MAS pace as mm:ss, e.g. 4:46.',
            'resting_heart_rate.required_with' => 'Enter your resting heart rate too, it is needed with your max heart rate.',
            'max_heart_rate.required_with' => 'Enter your maximum heart rate too, it is needed with your resting heart rate.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->physicalDataRules();
    }
}
