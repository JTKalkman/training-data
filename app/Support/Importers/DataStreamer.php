<?php

namespace App\Support\Importers;

use Illuminate\Support\Facades\Storage;

class DataStreamer
{
    private $handle;

    private $tmpPath;

    private $path;

    private bool $first = true;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->tmpPath = tempnam(sys_get_temp_dir(), 'samples_').'.json';
        $this->handle = fopen($this->tmpPath, 'w');
        fwrite($this->handle, '[');
    }

    public function write(array $dataPoint): void
    {
        if (! $this->first) {
            fwrite($this->handle, ',');
        }
        fwrite($this->handle, json_encode($dataPoint));
        $this->first = false;
    }

    public function close(): void
    {
        fwrite($this->handle, ']');
        fclose($this->handle);
        Storage::put($this->path, fopen($this->tmpPath, 'r'));
        unlink($this->tmpPath);
    }
}
