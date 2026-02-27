<?php

namespace App\Support\Parsers;

class ParsedRouteData
{
    public array $coordinates;

    public function __construct(array $coordinates)
    {
        $this->coordinates = $coordinates;
    }
}
