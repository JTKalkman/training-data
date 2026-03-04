<?php

namespace App\Support\Samplers\Contracts;

interface SimplifierInterface
{
    public function simplify(array $samples,): array;
}
