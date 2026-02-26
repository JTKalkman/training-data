<?php

namespace App\Support\Parsers;

class ParsedDeviceData
{
    public string $name;
 
    public string $externalId;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->externalId = $data['external_id'];
    }
}
