<?php

namespace App\Support\API\Contracts;

use Illuminate\Http\Client\Response;

interface ApiClientInterface {

    public static function get(string $path, array $params = [], array $headers = []): Response;

    public static function post(string $path, array $data = [], array $headers = []): Response;
};
