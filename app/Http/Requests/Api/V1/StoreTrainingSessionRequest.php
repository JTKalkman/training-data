<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrainingSessionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'platform' => ['required', 'string', 'in:polar,garmin'],
            'importMethod' => ['required', 'string', 'in:api,export'],
            'externalId' => ['required', 'string'],
            'startedAt' => ['required', 'date'],
            'payload' => ['required', 'array'],
        ];
    }
}
