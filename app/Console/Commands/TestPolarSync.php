<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Support\Sync\PolarProfileSync;
use Illuminate\Console\Command;

use function Laravel\Prompts\warning;

class TestPolarSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-polar-sync {userId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(PolarProfileSync $sync): void
    {
        $user = User::find($this->argument('userId'));

        if (! $user) {
            warning("Unknown user. User with ID " . $this->argument('userId') . " not found");
            return;
        }

        $sync->run($user);
    }
}
