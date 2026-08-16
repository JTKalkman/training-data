<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class RefreshSyncOnLogin
{
    public function handle(Login $event): void
    {
        $event->user->polarProfiles()
            ->whereNull('unlinked_at')
            ->where(function ($q) {
                $q->whereNull('last_synced_at')
                  ->orWhere('last_synced_at', '<', now()->subMinutes(10));
            })
            ->update(['next_sync_at' => now()]);
    }
}
