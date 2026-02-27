<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Support\PolarExerciseSync;
use Illuminate\Console\Command;

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
    public function handle(PolarExerciseSync $sync)
    {
        $user = User::find($this->argument('userId'));
        $sync->run($user);
    }
}
