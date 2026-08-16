<?php

namespace App\Console\Commands;

use App\Models\PolarProfile;
use App\Support\Sync\PolarProfileSync;
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
    public function handle(PolarProfileSync $sync): void
    {
        PolarProfile::whereNotNull('next_sync_at')
            ->where('next_sync_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('locked_at') // Not locked
                    ->orWhere('locked_at', '<', now()->subMinutes(10)); // Stale lock, reclaim these.
            })
            ->chunkById(100, function ($profiles) use ($sync) {
                foreach ($profiles as $profile) {
                    $sync->runProfile($profile);
                }
            });
    }
}
