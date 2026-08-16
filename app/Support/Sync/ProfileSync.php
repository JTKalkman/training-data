<?php

namespace App\Support\Sync;

use App\Models\DataSource;
use App\Models\SyncProfile;
use App\Models\TrainingSession;
use App\Models\User;
use App\Support\API\Exceptions\ApiAuthException;
use App\Support\Importers\TrainingSessionImporter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

abstract class ProfileSync
{
    abstract protected function profileQuery(User $user): Builder;

    abstract protected function fetchExercises(SyncProfile $profile): array;

    abstract protected function parser(): object;

    abstract protected function dataSourceName(): string;

    abstract protected function providerLabel(): string;

    protected function syncIntervalMinutes(): int
    {
        return 60;
    }

    public function run(User $user): array
    {
        $result = [
            'errors' => [], 
            'success' => false
        ];

        foreach ($this->profileQuery($user)->get() as $profile) {
            $this->syncProfile($profile, $result);
        }

        $result['success'] = count($result['errors']) === 0;

        return $result;
    }

    public function runProfile(SyncProfile $profile): array {
        $result = [
            'errors' => [],
            'success' => false
        ];

        $this->syncProfile($profile, $result);

        return $result;
    }

    protected function syncProfile(SyncProfile $profile, array &$result): void
    {
        $locked = $profile::where('id', $profile->id)
            ->where(function ($q) {
                $q->whereNull('locked_at')->orWhere('locked_at', '<', now()->subMinutes(10));
            })
            ->update(['locked_at' => now()]);

        if (! $locked) {
            return; // already being synced elsewhere
        }

        $profile = $profile->fresh();

        try {
            $exercises = $this->fetchExercises($profile);

            $dataSource = DataSource::where('name', $this->dataSourceName())->first();
            $importer = new TrainingSessionImporter;
            $parser = $this->parser();

            foreach ($exercises as $exercise) {
                $exerciseId = $exercise['id'];

                $exists = TrainingSession::where([
                    'external_id' => $exerciseId,
                    'user_id' => $profile->user->id,
                    'data_source_id' => $dataSource->id,
                ])->exists();

                if (! $exists) {
                    $importer->import($profile->user, $dataSource, $parser->parse($exercise));
                }
            }

            $profile->update([
                'last_synced_at' => now(),
                'last_sync_attempted_at' => now(),
                'last_sync_error' => null,
                'consecutive_sync_failures' => 0,
                'next_sync_at' => now()->addMinutes($this->syncIntervalMinutes()),
                'locked_at' => null,
            ]);
        } catch (ApiAuthException $e) {
            $profile->update([
                'last_sync_attempted_at' => now(),
                'last_sync_error' => $e->getMessage(),
                'consecutive_sync_failures' => $profile->consecutive_sync_failures + 1,
                'next_sync_at' => null, // needs relink, stop polling
                'locked_at' => null,
            ]);

            $result['errors'][] = [
                'profile_id' => $profile->id,
                'message' => $e->getMessage()
            ];
        } catch (\Throwable $th) {
            $failures = $profile->consecutive_sync_failures + 1;
            $backoff = min(60 * 24, 5 * (2 ** $failures)); // minutes, capped at 24h

            $profile->update([
                'last_sync_attempted_at' => now(),
                'last_sync_error' => $th->getMessage(),
                'consecutive_sync_failures' => $failures,
                'next_sync_at' => now()->addMinutes($backoff),
                'locked_at' => null,
            ]);
                
            $result['errors'][] = [
                'profile_id' => $profile->id,
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ];

            Log::error("{$this->providerLabel()}Sync failed", [
                'user_id' => $profile->user->id,
                'errors' => $th->getMessage(),
                'exception' => $th,
            ]);
        }
    }
}
