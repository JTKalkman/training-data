<?php

namespace Database\Seeders;

use App\Support\CsvReader;
use App\Support\Importers\TrainingSessionImporter;
use App\Support\Parsers\PolarCsvParser;
use Illuminate\Database\Seeder;

class TrainingSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parser = new PolarCsvParser;
        $polarDataBasePath = 'database/seeders/sample-data/polar';
        $importer = new TrainingSessionImporter;

        foreach (glob($polarDataBasePath.'/*.CSV') as $filePath) {
            $rows = CsvReader::from($filePath);
            $parsedSession = $parser->parse($rows);
            $session = $importer->import($parsedSession, 1); // Currently only one user.
        }
    }
}
