<?php

namespace App\Jobs;

use App\Models\PolarProfile;
use App\Support\Sync\PolarProfileSync;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncPolarProfileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1; // retry/backoff handled via next_sync_at, not queue retries.
    public int $timeout = 120;

    public function __construct(public PolarProfile $profile) {}

    public function handle(PolarProfileSync $sync): void
    {
        $sync->runProfile($this->profile);
    }
}
