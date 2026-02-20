<?php

namespace app\Support\API\Polar;

use App\Support\API\Contracts\ApiClientInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class PolarClient implements ApiClientInterface {

    const API_URL = 'https://www.polaraccesslink.com/v3/';

    public static function get(string $path, array $params = [], array $headers = []): Response
    {
        return Http::withHeaders($headers)
            ->get(self::API_URL . $path, $params);
    }

    public static function post(string $path, array $data = [], array $headers = []): Response
    {
        return Http::withHeaders($headers)
            ->post(self::API_URL . $path, $data);
    }
}
