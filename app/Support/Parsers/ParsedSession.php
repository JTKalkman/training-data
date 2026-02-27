<?php

namespace App\Support\Parsers;

class ParsedSession
{
    public ParsedDeviceData $deviceData;

    public ParsedSessionData $sessionData;

    public ParsedSummaryData $summary;

    public array $heartRateZones;

    public ParsedSampleData $sampleData;

    public ParsedRouteData $routeData;

    public function __construct(
        ParsedDeviceData $deviceData,
        ParsedSessionData $sessionData,
        ParsedSummaryData $summaryData,
        array $heartRateZones,
        ParsedSampleData $sampleData,
        ParsedRouteData $routeData,
    ) {
        $this->deviceData = $deviceData;
        $this->sessionData = $sessionData;
        $this->summary = $summaryData;
        $this->heartRateZones = $heartRateZones;
        $this->sampleData = $sampleData;
        $this->routeData = $routeData;
    }
}
