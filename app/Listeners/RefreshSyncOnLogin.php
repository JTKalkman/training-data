<?php

namespace App\Listeners;

use App\Jobs\SyncPolarProfileJob;
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
            ->get()
            ->each(fn ($profile) => SyncPolarProfileJob::dispatch($profile));
    }
}
