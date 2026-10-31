<?php

use App\Support\Services\PaceZoneGenerator;

function scheme(): array
{
    return [
        'anchor' => 'mas',
        'zones' => [
            1 => ['name' => 'Slow', 'from_pct' => 50,  'to_pct' => 100],
            2 => ['name' => 'Fast', 'from_pct' => 100, 'to_pct' => 200],
        ],
    ];
}

test('generate default pace zones', function () {
    $config = config('pace_zones');
    $mas = 240;
    $zones = (new PaceZoneGenerator())->generateDefaultPaceZones($config['polar_mas_5'], $mas);

    // Make it readable quickly.
    // var_dump(
    //     gmdate("i:s", $zones[0]['max_seconds_per_km']),
    //     gmdate("i:s", $zones[0]['min_seconds_per_km']),
    //     gmdate("i:s", $zones[1]['max_seconds_per_km']),
    //     gmdate("i:s", $zones[1]['min_seconds_per_km']),
    //     gmdate("i:s", $zones[2]['max_seconds_per_km']),
    //     gmdate("i:s", $zones[2]['min_seconds_per_km']),
    //     gmdate("i:s", $zones[3]['max_seconds_per_km']),
    //     gmdate("i:s", $zones[3]['min_seconds_per_km']),
    //     gmdate("i:s", $zones[4]['max_seconds_per_km']),
    //     gmdate("i:s", $zones[4]['min_seconds_per_km']),     
    // );

    $this->assertEquals(1200, $zones[0]['max_seconds_per_km']);
    $this->assertEquals(511,  $zones[0]['min_seconds_per_km']);
    $this->assertEquals(511,  $zones[1]['max_seconds_per_km']);
    $this->assertEquals(324,  $zones[1]['min_seconds_per_km']);
    $this->assertEquals(324,  $zones[2]['max_seconds_per_km']);
    $this->assertEquals(240,  $zones[2]['min_seconds_per_km']);
    $this->assertEquals(240,  $zones[3]['max_seconds_per_km']);
    $this->assertEquals(192,  $zones[3]['min_seconds_per_km']);
    $this->assertEquals(192,  $zones[4]['max_seconds_per_km']);
    $this->assertEquals(120,  $zones[4]['min_seconds_per_km']);
});

test('test_calculates_boundaries_from_mas', function () {
    $zones = (new PaceZoneGenerator())->generateDefaultPaceZones(scheme(), 300);

    $this->assertSame(300, $zones[0]['min_seconds_per_km']);
    $this->assertSame(600, $zones[0]['max_seconds_per_km']);
    $this->assertSame(150, $zones[1]['min_seconds_per_km']);
    $this->assertSame(300, $zones[1]['max_seconds_per_km']);
});

test('test_default_scheme_is_consistent', function () {
    $masValues = [210, 286, 300, 360];

    foreach ($masValues as $mas) {
        $zones = (new PaceZoneGenerator())
            ->generateDefaultPaceZones(config('pace_zones.polar_mas_5'), $mas);
    
        $this->assertCount(5, $zones);
    
        foreach ($zones as $zone) {
            $this->assertIsInt($zone['min_seconds_per_km']);
            $this->assertLessThan($zone['max_seconds_per_km'], $zone['min_seconds_per_km']);
        }
    
        // No gaps or overlaps: zone N+1's slow end is zone N's fast end.
        for ($i = 1; $i < count($zones); $i++) {
            $this->assertSame($zones[$i - 1]['min_seconds_per_km'], $zones[$i]['max_seconds_per_km']);
        }
    
        // MAS is the slow boundary of zone 4.
        $this->assertSame($mas, $zones[3]['max_seconds_per_km']);
    }
});
