<?php

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

        return redirect()->route('diary.showWeek', [
            'year' => $year,
            'week' => $week,
        ]);
    }

    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/diary/{year}/week/{week}', [WeekOverviewController::class, 'showWeek'])
    ->whereNumber('year')
    ->whereNumber('week')
    ->middleware(['auth', 'verified'])
    ->name('diary.showWeek');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
