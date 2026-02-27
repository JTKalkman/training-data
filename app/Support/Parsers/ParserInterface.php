<?php

namespace App\Support\Parsers;

interface ParserInterface
{
    public function createDeviceData(array $data): ParsedDeviceData;

    public function createSessionData(array $data): ParsedSessionData;

    public function createSummaryData(array $data): ParsedSummaryData;

    public function createHeartRateZones(array $data): array;

    public function createSampleData(array $data): ParsedSampleData;

    public function createRouteData(array $data): ParsedRouteData;

    public function parse(iterable $data): ParsedSession;
}
