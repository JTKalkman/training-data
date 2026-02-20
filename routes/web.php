<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\PolarAuthController;
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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/sessions/{session}/raw-data', [SessionController::class, 'rawData'])
        ->name('sessions.raw-data');
    
    Route::get('/sessions/{session}', [SessionController::class, 'show'])
        ->name('sessions.session');
    
    Route::get('/sessions/{year}/week/{week}', [WeekOverviewController::class, 'show'])
        ->whereNumber('year')
        ->whereNumber('week')
        ->name('sessions.week');

    Route::get('/account/settings', [AccountController::class, 'settings'])
        ->name('account.settings');
    
    Route::get('/auth/polar/callback', [PolarAuthController::class, 'callback'])
        ->name('auth.polar.callback');
    Route::get('/auth/polar/redirect', [PolarAuthController::class, 'redirect'])
        ->name('auth.polar.redirect');
});


Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
