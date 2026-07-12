<?php

namespace App\Jobs;

use App\Models\PolarProfile;
use App\Support\Sync\PolarProfileSync;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncPolarExercisesJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private PolarProfile $polarProfile
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new PolarProfileSync)->run($this->polarProfile->user);
    }
}
