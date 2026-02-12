<?php

namespace App\Support\Parsers;

class ParsedSessionData
{
    public ?int $sportType;

    public int $durationSeconds;

    public \Carbon\Carbon $startedAt;

    public string $source;

    public function __construct(array $data)
    {
        $this->sportType = $data['sport_type'];
        $this->durationSeconds = $data['duration_seconds'];
        $this->startedAt = $data['started_at'];
        $this->source = $data['source'];
    }
}
