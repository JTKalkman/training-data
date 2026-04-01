<?php

namespace App\Support\Parsers;

class ParsedSummaryData
{
    public ?int $minHeartRate;

    public ?int $avgHeartRate;

    public ?int $maxHeartRate;

    public ?int $minPace;

    public ?int $avgPace;

    public ?int $maxPace;

    public ?int $distance;

    public ?int $calories;

    public bool $hasRoute;

    public array $trainingLoad;

    public function __construct(array $data)
    {
        $this->minHeartRate = $data['min_heart_rate'];
        $this->avgHeartRate = $data['avg_heart_rate'];
        $this->maxHeartRate = $data['max_heart_rate'];
        $this->minPace = $data['min_pace'] ?? null;
        $this->avgPace = $data['avg_pace'] ?? null;
        $this->maxPace = $data['max_pace'] ?? null;
        $this->distance = $data['distance'];
        $this->calories = $data['calories'];
        $this->hasRoute = $data['has_route'];
        $this->trainingLoad = $data['training_load'];
    }
}
