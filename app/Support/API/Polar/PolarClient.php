<?php

namespace App\Support\API\Polar;

use App\Support\API\Contracts\ApiClientInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class PolarClient implements ApiClientInterface {

    const API_URL = 'https://www.polaraccesslink.com/v3/';

    protected static function throwException($response): void
    {
        throw new PolarApiException(
            'Polar API request failed: ' . $response->status() . ' ' . $response->body()
        );
    }

    public static function get(string $path, array $params = [], array $headers = []): Response
    {
        $response = Http::withHeaders($headers)
            ->get(self::API_URL . $path, $params);

        if ($response->failed()) {
            self::throwException($response);
        }

        return $response;
    }

    public static function post(string $path, array $data = [], array $headers = []): Response
    {
        $response = Http::withHeaders($headers)
            ->post(self::API_URL . $path, $data);

        if ($response->failed()) {
            self::throwException($response);
        }

        return $response;
    }
}
