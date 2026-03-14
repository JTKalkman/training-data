<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\PolarAuthController;
use App\Http\Controllers\TrainingSessionController;
use App\Http\Controllers\WeekOverviewController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('training-sessions');
})->middleware('auth')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/training-sessions/{session}/sample-data', [TrainingSessionController::class, 'sampleData'])
        ->name('training-sessions.sample-data');

    Route::get('/training-sessions/{session}/route-data', [TrainingSessionController::class, 'routeData'])
        ->name('training-sessions.route-data');

    Route::get('/training-sessions/{session}', [TrainingSessionController::class, 'show'])
        ->name('training-sessions.session');

    Route::get('/training-sessions/{year}/week/{week}', [WeekOverviewController::class, 'show'])
        ->whereNumber('year')
        ->whereNumber('week')
        ->name('training-sessions.week');

    Route::get('/training-sessions', function () {
        $now = Carbon::now();
        return redirect()->route('training-sessions.week', [
            'year' => $now->year,
            'week' => $now->weekOfYear,
        ]);
    })->name('training-sessions');

    Route::get('/polar-accounts', [AccountController::class, 'PolarAccounts'])
        ->name('polar-accounts');

    Route::get('/auth/polar/callback', [PolarAuthController::class, 'callback'])
        ->name('auth.polar.callback');

    Route::get('/auth/polar/redirect', [PolarAuthController::class, 'redirect'])
        ->name('auth.polar.redirect');
});

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
