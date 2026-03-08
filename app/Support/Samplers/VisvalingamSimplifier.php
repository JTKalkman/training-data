<?php

namespace App\Support\Samplers;

use App\Support\Samplers\Contracts\SimplifierInterface;

class VisvalingamSimplifier implements SimplifierInterface
{
    public function __construct(
        private string $xKey = 'lat',
        private string $yKey = 'lng',
        private float $target_ratio = 0.2, // 1/5
    ) {}

    public function simplify(array $samples): array
    {
        $simplified = $samples;
        $targetSize = (int) round(count($samples) * $this->target_ratio);

        while (count($simplified) > $targetSize) {
            $minArea = null;
            $minIndex = null;

            for ($i = 1; $i < count($simplified) - 1; $i++) {
                $x1 = $simplified[$i - 1][$this->xKey];
                $y1 = $simplified[$i - 1][$this->yKey];
                $x2 = $simplified[$i][$this->xKey];
                $y2 = $simplified[$i][$this->yKey];
                $x3 = $simplified[$i + 1][$this->xKey];
                $y3 = $simplified[$i + 1][$this->yKey];

                $area = abs($x1 * ($y2 - $y3) + $x2 * ($y3 - $y1) + $x3 * ($y1 - $y2)) / 2;

                if ($minArea === null || $area < $minArea) {
                    $minArea = $area;
                    $minIndex = $i;
                }
            }

            array_splice($simplified, $minIndex, 1);
        }

        return $simplified;
    }
}
