<?php

namespace App\Support\Parsers;

class ParsedSampleData
{
    public int $sampleRate;

    public array|string|null $heartRate;

    public array|string|null $speed;

    public array|string|null $cadence;

    public array|string|null $altitude;

    public array|string|null $temperature;

    public array|string|null $distance;

    public function __construct(array $data)
    {
        $this->sampleRate = $data['sample_rate'];
        $this->heartRate = $data['heart_rate'] ?? null;
        $this->speed = $data['speed'] ?? null;
        $this->cadence = $data['cadence'] ?? null;
        $this->altitude = $data['altitude'] ?? null;
        $this->temperature = $data['temperature'] ?? null;
        $this->distance = $data['distance'] ?? null;
    }
}
