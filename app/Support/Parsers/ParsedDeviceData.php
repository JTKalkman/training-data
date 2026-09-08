<?php

namespace App\Support\Parsers;

class ParsedDeviceData
{
    public null|string $name;

    public null|string $externalId;

    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? null;
        $this->externalId = $data['external_id'] ?? null;
    }
}
