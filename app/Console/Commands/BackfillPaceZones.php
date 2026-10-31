<?php

namespace App\Console\Commands;

use App\Models\PaceZoneSet;
use App\Models\TrainingSession;
use Illuminate\Console\Command;

class BackfillPaceZones extends Command
{
    protected $signature = 'app:backfill-pace-zones';
    
    protected $description = 'Backfill pace zones in running training sessions.';

    public function handle()
    {
        $progressBar = $this->output->createProgressBar();
    
        $TrainingSessions = TrainingSession::whereHas('sportType', fn ($q) => $q->where('name', 'running'))
            ->whereNull('pace_zone_set_id')
            ->with('user.physicalData')
            ->lazy(200)
            ->each(function (TrainingSession $trainingSession) use ($progressBar) {
                $progressBar->advance();

                $user = $trainingSession->user;
                $physicalData = $user->physicalData;
                $mas = $physicalData->mas_seconds_per_km;

                if ($mas === null) return;

                $set = $physicalData->activePaceZoneSet
                    ?? PaceZoneSet::forMas($user, $mas);

                $trainingSession->update([
                    'pace_zone_set_id' => $set->id,
                    'pace_zone_set_is_estimated' => true,
                ]);                
            });

        $progressBar->finish();
        $this->newLine();
        $this->info('Done.');
    }
}
