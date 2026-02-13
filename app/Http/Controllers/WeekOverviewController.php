<?php

namespace App\Http\Controllers;

use App\Http\Resources\TrainingSessionResource;
use App\Models\TrainingSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WeekOverviewController extends Controller
{
    public function show (int $year, int $week)
    {
        $user = Auth::user();

        $weekDate = Carbon::now()->setISODate($year, $week);
        $startOfWeek = $weekDate->copy()->startOfWeek()->startOfDay()->utc();
        $endOfWeek   = $weekDate->copy()->endOfWeek()->endOfDay()->utc();
        $prevWeek = $weekDate->copy()->subWeek();
        $nextWeek = $weekDate->copy()->addWeek();
        
        $trainingSessions = TrainingSession::where('user_id', $user->id)
            ->whereBetween('started_at', [$startOfWeek, $endOfWeek])
            ->with([
                'sportType',
                'trainingSummary'
            ])
            ->orderBy('started_at')
            ->get();

        $navigation = [
            'prev' => [
                'year' => $prevWeek->isoWeekYear,
                'week' => $prevWeek->isoWeek,
                'url'  => route('diary.week', [$prevWeek->isoWeekYear, $prevWeek->isoWeek]),
            ],
            'next' => [
                'year' => $nextWeek->isoWeekYear,
                'week' => $nextWeek->isoWeek,
                'url'  => route('diary.week', [$nextWeek->isoWeekYear, $nextWeek->isoWeek]),
            ],
        ];

        return Inertia::render('Diary/Week', [
            'trainingSessions' => TrainingSessionResource::collection($trainingSessions),
            'year' => $year,
            'week' => $week,
            'navigation' => $navigation
        ]);
    }
}
