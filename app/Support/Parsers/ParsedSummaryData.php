<?php

namespace App\Support\Parsers;

class ParsedSummaryData
{
    public ?int $minHeartRate;

    public ?int $avgHeartRate;

    public ?int $maxHeartRate;

    public ?int $distance;

    public ?int $calories;

    public bool $hasRoute;

    public array $trainingLoad;

    public function __construct(array $data)
    {
        $this->minHeartRate = $data['min_heart_rate'];
        $this->avgHeartRate = $data['avg_heart_rate'];
        $this->maxHeartRate = $data['max_heart_rate'];
        $this->distance = $data['distance'];
        $this->calories = $data['calories'];
        $this->hasRoute = $data['has_route'];
        $this->trainingLoad = $data['training_load'];
    }
}
