<?php

namespace Database\Seeders;

use App\Models\DataSource;
use App\Models\User;
use App\Support\CsvReader;
use App\Support\Importers\TrainingSessionImporter;
use App\Support\Parsers\PolarCsvParser;
use App\Support\Parsers\PolarExportJsonParser;
use Illuminate\Database\Seeder;

class TrainingSessionExportSeeder extends Seeder
{
    /**
     * Run the database seeds for a selected user.
     * 
     * SEED_USER_ID=1 php artisan db:seed --class=TrainingSessionExportSeeder
     */
    public function run(): void
    {
        $userId = env('SEED_USER_ID', 1);

        $user = User::find(['id' => $userId])->first();
        $parser = new PolarExportJsonParser;
        $polarDataBasePath = 'database/seeders/sample-data/polar/json';
        $importer = new TrainingSessionImporter;
        $dataSource = DataSource::where('name', 'polar')->first();

        foreach (glob($polarDataBasePath.'/*.json') as $filePath) {
            $data = json_decode(file_get_contents($filePath), true);

            $filename = basename($filePath, '.json');
            $externalId = preg_replace('/^training-session-\d{4}-\d{2}-\d{2}-/', '', $filename);
            
            foreach($data['exercises'] as $exercise) {
                $exerciseData = [
                    // device.
                    'deviceId' => $data['deviceId'] ?? null,
                    
                    // Training session.
                    'sport' => $exercise['sport'] ?? null,
                    'startTime' => $exercise['startTime'] ?? null,
                    'timezoneOffset' => $exercise['timezoneOffset'] ?? null,
                    'duration' => $exercise['duration'] ?? null,
                    'externalId' => $externalId,

                    // Training summary.
                    'minHeartRate' => $exercise["heartRate"]['min'],
                    'avgHeartRate' => $exercise["heartRate"]['avg'],
                    'maxHeartRate' => $exercise["heartRate"]['max'],
                    'distance' => $exercise['distance'] ?? null,
                    'calories' => $exercise['kiloCalories'] ?? null,
                    'has_route' => !empty($exercise['samples']['recordedRoute']),
                    'training_load' => [
                        'training_load' => $exercise['loadInformation'] ?? null,
                        'training_load_pro' => null,
                    ],

                    // Heart reate zones.
                    'heart_rate_zones' => $exercise['zones']['heart_rate'],

                    // Sample data.
                    'samples' => [
                        'heart_rate' => $exercise['samples']['heartRate'] ?? [],
                        'altitude' => $exercise['samples']['altitude'] ?? [],
                        'speed' => $exercise['samples']['speed'] ?? [],
                        'cadence' => $exercise['samples']['cadence'] ?? [],
                        'distance' => $exercise['samples']['distance'] ?? [],
                        'temperature' => $exercise['samples']['temperature'] ?? [],
                        'pace' => $exercise['samples']['pace'] ?? [],
                    ],

                    // route.
                    'route' => $exercise['samples']['recordedRoute'] ?? [],
                ];

                $parsedSession = $parser->parse($exerciseData);

                $session = $importer->import($user, $dataSource, $parsedSession);

                sleep(1);
            }
        }
    }
}
