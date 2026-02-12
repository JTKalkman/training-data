<?php

namespace App\Support\Parsers;

class ParsedSummaryData
{
    public int $minHeartRate;

    public int $avgHeartRate;

    public int $maxHeartRate;

    public function __construct(array $data)
    {
        $this->minHeartRate = $data['min_heart_rate'];
        $this->avgHeartRate = $data['avg_heart_rate'];
        $this->maxHeartRate = $data['max_heart_rate'];
    }
}
