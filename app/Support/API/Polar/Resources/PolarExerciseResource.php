<?php

namespace App\Support\API\Polar\Resources;

use App\Support\API\Polar\PolarClient;

class PolarExerciseResource
{
    public static function list(string $accessToken): array
    {
        $response = PolarClient::get(
            'exercises',
            [
                'samples' => true,
                'zones' => true,
                'route' => true,
            ],
            [
                ...PolarClient::bearerHeader($accessToken),
                'Accept' => 'application/json',
            ]
        );

        return $response->json();
    }

    public static function get(string $accessToken, string $exerciseId): array|null
    {
        $response = PolarClient::get(
            "exercises/{$exerciseId}",
            [
                'samples' => true,
                'zones' => true,
                'route' => true,
            ],
            [
                ...PolarClient::bearerHeader($accessToken),
                'Accept' => 'application/json',
            ]
        );

        return $response->json();
    }
}
