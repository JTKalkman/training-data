<?php

use App\Console\Commands\SyncPolarProfiles;
use App\Models\PaceZoneSet;
use Illuminate\Support\Facades\Schedule;

Schedule::command(SyncPolarProfiles::class)
    ->everyMinute()
    ->withoutOverlapping();

Schedule::call(function () {
    PaceZoneSet::where('created_at', '<', now()->subDay())
        ->whereDoesntHave('trainingSessions')
        ->whereDoesntHave('activeForPhysicalData')
        ->delete();
})->daily();
