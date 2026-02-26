<?php

namespace App\Support\Parsers;

class ParsedSession
{
    public ParsedDeviceData $deviceData;

    public ParsedSessionData $sessionData;

    public ParsedSummaryData $summary;

    public array $heartRateZones;

    public array $rawData;

    public function __construct(
        ParsedDeviceData $deviceData,
        ParsedSessionData $sessionData,
        ParsedSummaryData $summaryData,
        array $heartRateZones = [],
        array $rawData = []
    ) {
        $this->deviceData = $deviceData;
        $this->sessionData = $sessionData;
        $this->summary = $summaryData;
        $this->heartRateZones = $heartRateZones;
        $this->rawData = $rawData;
    }
}
