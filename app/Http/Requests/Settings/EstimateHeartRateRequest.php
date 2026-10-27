<?php

namespace App\Http\Requests\Settings;

use App\Concerns\EstimateHeartRateRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EstimateHeartRateRequest extends FormRequest
{
    use EstimateHeartRateRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date_of_birth' => $this->dateOfBirthRules()
        ];
    }
}
