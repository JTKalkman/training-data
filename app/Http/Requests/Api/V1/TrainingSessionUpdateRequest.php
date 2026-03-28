<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class TrainingSessionUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rating' => ['nullable', 'integer', 'min:1', 'max:5', 'prohibits:ratingString'],
            'ratingString' => ['nullable', 'string', 'in:horrible,poor,ok,great,excellent', 'prohibits:rating'],
            'notes' => ['nullable', 'string', 'max:1000', function($attr, $value, $fail) {
                if ($value !== strip_tags($value)) {
                    $fail('HTML is not allowed.');
                }
            }],
        ];
    }
}
