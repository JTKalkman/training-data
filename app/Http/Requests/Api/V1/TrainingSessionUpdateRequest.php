<?php

namespace App\Http\Requests\Api\V1;

use App\Models\TrainingSession;
use Illuminate\Foundation\Http\FormRequest;

class TrainingSessionUpdateRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'ratingString' => $this->ratingString === '' ? null : $this->ratingString,
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rating' => [
                'nullable', 
                'integer', 
                'in:' . implode(',', array_keys(TrainingSession::RATING_MAP)), 
                'prohibits:ratingString'
            ],
            'ratingString' => [
                'nullable', 
                'string', 
                'in:' . implode(',', TrainingSession::RATING_MAP), 
                'prohibits:rating'
            ],
            'notes' => ['nullable', 'string', 'max:1000', function($attr, $value, $fail) {
                if ($value !== strip_tags($value)) {
                    $fail('HTML is not allowed.');
                }
            }],
        ];
    }
}
