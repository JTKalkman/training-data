<?php

namespace App\Http\Requests\Settings;

use App\Concerns\TrainingSettingsValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TrainingSettingsUpdateRequest extends FormRequest
{
    use TrainingSettingsValidationRules;

    private function parsePaceToSeconds(string $pace): ?int
    {
        if (!preg_match('/^(\d+):([0-5]\d)$/', trim($pace), $matches)) {
            return null; // will fail integer/required validation naturally
        }

        return ((int) $matches[1] * 60) + (int) $matches[2];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('mas_pace')) {
            $this->merge([
                'mas_seconds_per_km' => $this->parsePaceToSeconds($this->input('mas_pace')),
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'mas_pace.regex' => 'Enter your MAS pace as mm:ss, e.g. 4:46.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->trainingSettingsRules();
    }
}
