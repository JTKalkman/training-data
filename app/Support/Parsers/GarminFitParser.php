<?php

namespace App\Support\Parsers;

class GarminFitParser implements ParserInterface
{
    public function createDeviceData(array $data): ParsedDeviceData
    {
        return new ParsedDeviceData([]);
    }

    public function createSessionData(array $data): ParsedSessionData
    {
        return new ParsedSessionData([]);
    }

    public function createSummaryData(array $data): ParsedSummaryData
    {
        return new ParsedSummaryData([]);
    }

    public function createHeartRateZones(array $data): array
    {
        return [];
    }

    public function createSampleData(array $data): ParsedSampleData
    {
        return new ParsedSampleData([]);
    }

    public function createRouteData(array $data): ParsedRouteData
    {
        return new ParsedRouteData([]);
    }

    public function parse(iterable $data): ParsedSession
    {
        $deviceData = $this->createDeviceData($data);
        $sessionData = $this->createSessionData($data);
        $summaryData = $this->createSummaryData($data);
        $heartRateZones = $this->createHeartRateZones($data);
        $sampleData = $this->createSampleData($data);
        $routeData = $this->createRouteData($data);

        return new ParsedSession($deviceData, $sessionData, $summaryData, $heartRateZones, $sampleData, $routeData);
    }
}
