<?php

namespace App\Support\Parsers;

class ParsedHeartRateZoneData
{
    public int $zoneNumber;

    public ?string $name;

    public int $minBpm;

    public int $maxBpm;

    public ?string $color;

    public ?int $inZoneSeconds;

    public function __construct(array $data)
    {
        $this->zoneNumber = $data['zone_number'];
        $this->name = $data['name'];
        $this->minBpm = $data['min_bpm'];
        $this->maxBpm = $data['max_bpm'];
        $this->color = $data['color'];
        $this->inZoneSeconds = $data['in_zone_seconds'];
    }
}
