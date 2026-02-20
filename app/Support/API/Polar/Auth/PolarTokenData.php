<?php

namespace App\Support\API\Polar\Auth;

class PolarTokenData
{
    public function __construct(
        public readonly string $accessToken,
        public readonly string $tokenType,
        public readonly int $expiresIn,
        public readonly int $xUserId,
    ) {}
}
