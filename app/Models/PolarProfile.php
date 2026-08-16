<?php

namespace App\Models;

class PolarProfile extends SyncProfile
{
    protected $fillable = [
        'user_id',
        'linked_at',
        'unlinked_at',
        'last_synced_at',
        'last_sync_attempted_at',
        'last_sync_error',
        'next_sync_at',
        'locked_at',
        'consecutive_sync_failures',
        'polar_user_id',
        'access_token',
        'token_expires_at',
        'first_name',
        'last_name',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
    ];
}
