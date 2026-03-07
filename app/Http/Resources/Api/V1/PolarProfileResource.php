<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PolarProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'linkedAt' => $this->linked_at?->toDateString(),
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'unlinkedAt' => $this->unlinked_at?->toDateString(),
        ];
    }
}
