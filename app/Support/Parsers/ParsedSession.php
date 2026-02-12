<?php

namespace App\Support\Parsers;

class ParsedSession
{
    public ParsedSessionData $sessionData;

    public ParsedSummaryData $summary;

    public array $heartRateZones;

    public array $rawData;

    public function __construct(
        ParsedSessionData $sessionData,
        ParsedSummaryData $summary,
        array $heartRateZones = [],
        array $rawData = []
    ) {
        $this->sessionData = $sessionData;
        $this->summary = $summary;
        $this->heartRateZones = $heartRateZones;
        $this->rawData = $rawData;
    }
}
