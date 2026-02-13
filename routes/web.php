<?php

use App\Http\Controllers\SessionController;
use App\Http\Controllers\WeekOverviewController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {

    if (Auth::user()) {
        $now = Carbon::now();
        $year = $now->year;
        $week = $now->weekOfYear;

        return redirect()->route('sessions.week', [
            'year' => $year,
            'week' => $week,
        ]);
    }

    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/sessions/{session}/raw-data', [SessionController::class, 'rawData'])
    ->middleware(['auth', 'verified'])
    ->name('sessions.raw-data');

Route::get('/sessions/{session}', [SessionController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('sessions.session');

Route::get('/sessions/{year}/week/{week}', [WeekOverviewController::class, 'show'])
    ->whereNumber('year')
    ->whereNumber('week')
    ->middleware(['auth', 'verified'])
    ->name('sessions.week');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
