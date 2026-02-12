<?php 

namespace App\Support;

use League\Csv\Reader;

class CsvReader
{
    public static function from(string $filePath): iterable
    {
        $csv = Reader::from($filePath, 'r');
        return $csv->getRecords();
    }
}
