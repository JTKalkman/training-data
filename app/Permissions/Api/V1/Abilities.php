<?php

namespace App\Permissions\Api\V1;

use App\Models\User;

final class Abilities
{
    public const TRAINING_SESSIONS_READ = 'training-sessions:read';
    public const TRAINING_SESSIONS_WRITE = 'training-sessions:write';

    public const PROFILE_READ = 'profile:read';
    public const PROFILE_WRITE = 'profile:write';

    // TODO: Create abilities for guest and authorized users.
    public const GUEST_ABILITIES = [];
    public const USER_ABILITIES = [
        self::TRAINING_SESSIONS_READ,
        self::TRAINING_SESSIONS_WRITE,
        self::PROFILE_READ,
        self::PROFILE_WRITE,
    ];
        
    public static function get(User $user): array
    {
        // TODO: Add guest users.
        return self::USER_ABILITIES;
    }
}
