<?php

namespace App\Support;

use App\Models\DataSource;
use App\Models\PolarProfile;
use App\Models\TrainingSession;
use App\Models\User;
use App\Support\API\Polar\Resources\PolarExerciseResource;
use App\Support\Importers\TrainingSessionImporter;
use App\Support\Parsers\PolarJsonParser;
use Illuminate\Support\Facades\Log;

class PolarExerciseSync
{
    public static function run(User $user)
    {
        $result = [
            'errors' => [],
            'success' => false,
        ];

        $polarProfiles = PolarProfile::where('user_id', $user->id)->get();

        foreach ($polarProfiles as $polarProfile) {
            try {
                $exercises = PolarExerciseResource::list(decrypt($polarProfile->access_token));

                $polarSource = DataSource::where('name', 'polar')->first();
                $importer = new TrainingSessionImporter;

                foreach ($exercises as $exercise) {
                    $exerciseId = $exercise['id'];
                    $trainingSession = TrainingSession::where('external_id', $exerciseId)
                        ->whereHas('dataSource', fn ($q) => $q->where('name', 'polar'))
                        ->first();

                    if ($trainingSession !== null) continue;

                    $parser = new PolarJsonParser;
                    $parsedExercise = $parser->parse($exercise);
                    $trainingSession = $importer->import($user, $polarSource, $parsedExercise);
                }

            } catch (\Throwable $th) {
                $result['errors'][] = $th->getMessage();
            }
        }

        if (count($result['errors']) === 0) {
            $result['success'] = true;
        }

        if (count($result['errors']) > 0) {
            Log::error('PolarExerciseSync failed', [
                'user_id' => $user->id,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
        }

        return $result;
    }
}
