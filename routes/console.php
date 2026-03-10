<?php

use App\Jobs\SyncPolarExercisesJob;
use App\Models\PolarProfile;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    PolarProfile::all()->each(fn($profile) => 
        dispatch(new SyncPolarExercisesJob($profile))
    );
})->daily();