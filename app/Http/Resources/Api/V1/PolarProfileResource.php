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
            'linkedAt' => $this->linked_at?->toIso8601String(),
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'unlinkedAt' => $this->unlinked_at?->toIso8601String(),
            'lastSyncedAt' => $this->last_synced_at?->toIso8601String(),
            'syncStatus' => $this->sync_status,
        ];
    }
}
