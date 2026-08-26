<?php

namespace App\Console\Commands;

use App\Jobs\SyncPolarProfileJob;
use App\Models\PolarProfile;
use Illuminate\Console\Command;

class SyncPolarProfiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-polar-profiles';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        PolarProfile::whereNotNull('next_sync_at')
            ->where('next_sync_at', '<=', now())
            ->where(function ($q) {
                // Not locked or stale lock, reclaim these.
                $q->whereNull('locked_at')->orWhere('locked_at', '<', now()->subMinutes(10)); 
            })
            ->chunkById(100, function ($profiles) {
                $profiles->each(fn ($profile) => SyncPolarProfileJob::dispatch($profile));
            });
    }
}
