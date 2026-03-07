<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
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
            'name' => $this->name,
            'platform' => $this->whenLoaded('dataSource', function($data) {
                return [
                    'name' => $data->name,
                    'label' => $data->label,
                ];
            }),
            'linkedAt' => $this->created_at?->toISOString(),
        ];
    }
}
