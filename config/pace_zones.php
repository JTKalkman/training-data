<?php

/**
 * Percentages of the anchor’s speed (100 = anchor pace); higher is faster.
 */
return [
    'polar_mas_5'   => [
        'anchor' => 'mas', 
        'zones' => [
            1 => [
                'name' => 'Very easy',
                'from_pct' => 20,
                'to_pct' => 47,
            ],
            2 => [
                'name' => 'Easy',
                'from_pct' => 47,
                'to_pct' => 74
            ],
            3 => [
                'name' => 'Moderate',
                'from_pct' => 74,
                'to_pct' => 100, // MAS seconds per km.
            ],
            4 => [
                'name' => 'Threshold',
                'from_pct' => 100, // MAS seconds per km.
                'to_pct' => 125
            ],
            5 => [
                'name' => 'Fast',
                'from_pct' => 125,
                'to_pct' => 200
            ],
        ]
    ],
    'threshold_7'   => [
        'anchor' => 'threshold',
        'zones' => [
            1 => [
                'name' => 'Easy',
                'from_pct' => 40,
                'to_pct' => 75
            ],
            2 => [
                'name' => 'Aerobic',
                'from_pct' => 75,
                'to_pct' => 88
            ],
            3 => [
                'name' => 'Temp',
                'from_pct' => 88,
                'to_pct' => 94
            ],
            4 => [
                'name' => 'Sub-lactate threshold',
                'from_pct' => 94,
                'to_pct' => 100
            ],
            5 => [
                'name' => 'Super-lactate threshold',
                'from_pct' => 100,
                'to_pct' => 106
            ],
            6 => [
                'name' => 'Vo2max',
                'from_pct' => 106, 
                'to_pct' => 115
            ],
            7 => [
                'name' => 'Peak output',
                'from_pct' => 115,
                'to_pct' => 150
            ],
        ]
    ],
];
