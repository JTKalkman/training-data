<?php

namespace App\Support\API\Polar\Resources;

use App\Support\API\Polar\PolarApiException;
use App\Support\API\Polar\PolarClient;

class PolarUserResource
{
    public static function registerUser(string $userId, string $accessToken): bool
    {
        try {
            PolarClient::post('users',
                ['member-id' => $userId],
                ['Authorization' => 'Bearer '.$accessToken]
            );
        } catch (PolarApiException $e) {
            // 409 = already registered, that's fine
            if (! str_contains($e->getMessage(), '409')) {
                throw $e;
            }
        }

        return true;
    }
}
