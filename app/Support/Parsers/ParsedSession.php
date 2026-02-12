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
        ParsedSummaryData $summaryData,
        array $heartRateZones = [],
        array $rawData = []
    ) {
        $this->sessionData = $sessionData;
        $this->summary = $summaryData;
        $this->heartRateZones = $heartRateZones;
        $this->rawData = $rawData;
    }
}
