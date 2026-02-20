<?php

namespace App\Support\API\Polar\Auth;

use App\Support\API\Polar\PolarApiException;
use App\Support\API\Polar\PolarClient;
use Illuminate\Support\Facades\Http;

class PolarAuthService
{
    public static function getAuthorizationUrl(string $state): string
    {
        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => config('services.polar.client_id'),
            'redirect_uri' => config('services.polar.redirect_uri'),
            'state' => $state,
        ]);

        return 'https://flow.polar.com/oauth2/authorization?'.$query;
    }

    public static function exchangeToken(string $code): PolarTokenData
    {
        $headers = [
            'Authorization' => 'Basic '.base64_encode(config('services.polar.client_id').':'.config('services.polar.client_secret')),
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json;charset=UTF-8',
        ];

        $response = Http::withHeaders($headers)
            ->asForm()
            ->post(
                'https://polarremote.com/v2/oauth2/token',
                [
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => config('services.polar.redirect_uri'),
                ]
            );

        if ($response->successful()) {
            $data = $response->json();

            return new PolarTokenData(
                $data['access_token'],
                $data['token_type'],
                $data['expires_in'],
                $data['x_user_id'],
            );
        }

        throw new PolarAuthException('Polar token exchange failed: '.$response->body());
    }

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
