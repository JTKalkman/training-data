<?php

namespace App\Support\API\Polar\Auth;

use App\Models\PolarProfile;
use App\Models\User;

class PolarTokenManager
{
    public static function store(User $user, PolarTokenData $tokenData): PolarProfile
    {
        return PolarProfile::updateOrCreate([
            'user_id' => $user->id,
            'polar_user_id' => $tokenData->xUserId,
        ], [
            'access_token' => encrypt($tokenData->accessToken),
            'token_expires_at' => now()->addSeconds($tokenData->expiresIn),
            'linked_at' => now(),
            'unlinked_at' => null,
        ]);
    }

    public static function get(User $user)
    {
        // Gets a users token.
    }

    public static function isExpired()
    {
        // Checks wether or not a token is still valid.
    }

    public static function refresh()
    {
        // Requests a new token. Might not be needed because a token has lifespan of almost 10 years.
    }
}
