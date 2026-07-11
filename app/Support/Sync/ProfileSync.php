<?php

namespace App\Support\Sync;

use App\Models\DataSource;
use App\Models\PolarProfile;
use App\Models\TrainingSession;
use App\Models\User;
use App\Support\Importers\TrainingSessionImporter;
use Illuminate\Support\Facades\Log;

abstract class ProfileSync
{
    abstract protected function profileQuery(User $user);

    abstract protected function fetchExercises(PolarProfile $profile): array;

    abstract protected function parser(): object;

    abstract protected function dataSourceName(): string;

    abstract protected function providerLabel(): string;

    public function run(User $user): array
    {
        $result = [
            'errors' => [], 
            'success' => false
        ];

        $profiles = $this->profileQuery($user)->get();

        foreach ($profiles as $profile) {
            $this->syncProfile($profile, $user, $result);
        }

        $result['success'] = count($result['errors']) === 0;
        // Map the errors to include only the message and trace

        return $result;
    }

    protected function syncProfile(PolarProfile $profile, User $user, array &$result): void
    {
        try {
            $exercises = $this->fetchExercises($profile);

            $dataSource = DataSource::where('name', $this->dataSourceName())->first();
            $importer = new TrainingSessionImporter;
            $parser = $this->parser();

            foreach ($exercises as $exercise) {
                $exerciseId = $exercise['id'];

                $exists = TrainingSession::where([
                    'external_id' => $exerciseId,
                    'user_id' => $user->id,
                    'data_source_id' => $dataSource->id,
                ])->exists();

                if ($exists) {
                    continue;
                }

                $importer->import($user, $dataSource, $parser->parse($exercise));
            }

            $profile->update([
                'last_synced_at' => now(),
                'last_sync_attempted_at' => now(),
                'last_synced_error' => null,
                'consecutive_sync_errors' => 0,
            ]);
        } catch (\Throwable $th) {
            $profile->update([
                'last_sync_attempted_at' => now(),
                'last_synced_error' => $th->getMessage(),
                'consecutive_sync_errors' => $profile->consecutive_sync_errors + 1,
            ]);
                
            $result['errors'][] = [
                'profile_id' => $profile->id,
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ];

            Log::error("{$this->providerLabel()}Sync failed", [
                'user_id' => $user->id,
                'errors' => $th->getMessage(),
                'exception' => $th,
            ]);
        }
    }
}
