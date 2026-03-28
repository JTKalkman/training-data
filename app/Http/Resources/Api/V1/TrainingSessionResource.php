<?php

namespace App\Http\Resources\Api\V1;

use App\Models\TrainingSession;
use App\Support\Duration;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingSessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $startedAt = Carbon::parse($this->started_at)
            ->setTimezone(CarbonTimeZone::createFromMinuteOffset($this->utc_offset))
            ->toIso8601String();

        return [
            'id' => $this->id,
            'startedAt' => $startedAt,
            'utcOffset' => $this->utc_offset,
            'durationSeconds' => $this->duration_seconds,
            'durationIso' => Duration::toIso($this->duration_seconds),
            'rating' => $this->rating,
            'ratingString' => TrainingSession::RATING_MAP[$this->rating] ?? '',
            'notes' => $this->notes,
            'device' => $this->whenLoaded('device', function () {
                return DeviceResource::make($this->device);
            }),
            'platform' => $this->whenLoaded('dataSource', function () {
                return DeviceResource::make($this->dataSource);
            }),
            'trainingSummary' => $this->whenLoaded('trainingSummary', function () {
                return TrainingSummaryResource::make($this->trainingSummary);
            }),
            'heartRateZones' => $this->whenLoaded('heartRateZones', function () {
                return HeartRateZoneResource::collection($this->heartRateZones);
            }),
        ];

        return parent::toArray($request);
    }
}
