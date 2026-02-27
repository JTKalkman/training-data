<?php

namespace App\Support\Parsers;

use Carbon\Carbon;

class ParsedSessionData
{
    public ?int $sportTypeId;

    public int $durationSeconds;

    public Carbon $startedAt;

    public int $UtcOffset;

    public int $dataSourceId;

    public string $externalId;

    public function __construct(array $data)
    {
        $this->sportTypeId = $data['sport_type_id'];
        $this->durationSeconds = $data['duration_seconds'];
        $this->startedAt = $data['started_at'];
        $this->UtcOffset = $data['utc_offset'];
        $this->dataSourceId = $data['data_source_id'];
        $this->externalId = $data['external_id'];
    }
}
