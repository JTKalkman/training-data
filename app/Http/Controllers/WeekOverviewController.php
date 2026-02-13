<?php

namespace App\Http\Controllers;

use App\Models\TrainingSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WeekOverviewController extends Controller
{
    public function showWeek (int $year, int $week)
    {
        $user = Auth::user();

        $weekDate = Carbon::now()->setISODate($year, $week);
        $startOfWeek = $weekDate->copy()->startOfWeek()->startOfDay()->utc();
        $endOfWeek   = $weekDate->copy()->endOfWeek()->endOfDay()->utc();
        
        $trainingSessions = TrainingSession::where('user_id', $user->id)
            ->whereBetween('started_at', [$startOfWeek, $endOfWeek])
            ->with([
                'sportType',
                'trainingSummary'
            ])
            ->orderBy('started_at')
            ->get();

        return Inertia::render('Diary/Week', [
            'trainingSessions' => $trainingSessions,
            'year' => $year,
            'week' => $week,
        ]);
    }
}
