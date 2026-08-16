<?php

use App\Console\Commands\SyncPolarProfiles;
use Illuminate\Support\Facades\Schedule;

Schedule::command(SyncPolarProfiles::class)
    ->everyMinute()
    ->withoutOverlapping();
