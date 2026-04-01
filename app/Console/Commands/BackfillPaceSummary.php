<?php

namespace App\Console\Commands;

use App\Models\SportType;
use App\Models\TrainingSession;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackfillPaceSummary extends Command
{
    protected $signature   = 'app:backfill-pace-summary';
    
    protected $description = 'Backfill min/max pace seconds in training summaries from stored JSON files';


    public function handle()
    {
        $progressBar = $this->output->createProgressBar();
        
        $TrainingSessions = TrainingSession::with('trainingSummary')
            ->whereHas('trainingSummary')
            ->whereHas('sportType', fn ($q) => $q->where('name', 'running'))
            ->lazy(200)
            ->each(function (TrainingSession $trainingSessions) use ($progressBar) {
                $path = $trainingSessions->sampleDataPath();

                if (! Storage::exists($path)) {
                    $this->warn("No JSON file for session {$trainingSessions->id}, skipping.");
                    return;
                }

                $samples = json_decode(Storage::get($path), true);

                $paceValues = collect($samples)
                    ->pluck('pace')
                    ->filter(fn ($pace) => $pace > 210 && $pace <= 1200); // Avoids unrealistic samples.

                if ($paceValues->isEmpty()) {
                    $this->warn("No valid pace data for session {$trainingSessions->id}, skipping.");
                    return;
                }

                $trainingSessions->trainingSummary->update([
                    'min_pace_seconds' => $paceValues->min(),
                    'avg_pace_seconds' => (int) round($paceValues->average()),
                    'max_pace_seconds' => $paceValues->max(),
                ]);

                unset($samples);
                unset($paceValues);
                
                $progressBar->advance();
            });

        $progressBar->finish();
        $this->newLine();
        $this->info('Done.');
    }
}
