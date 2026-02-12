<?php

namespace App\Support\Parsers;

class GarminFitParser implements ParserInterface
{
    public function createSessionData(array $headerData): ParsedSessionData
    {
        //
    }

    public function createSummaryData(array $headerData): ParsedSummaryData
    {
        //
    }

    public function createRawDataRecord(array $rawData): array
    {
        //
    }

    public function createHeartRateZones(): array
    {
        //
    }

    public function parse(iterable $rows) // : ParsedSession
    {
        //
    }
}
