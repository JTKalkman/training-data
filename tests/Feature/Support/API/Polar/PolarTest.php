<?php

use App\Support\API\Polar\PolarClient;
use Illuminate\Support\Facades\Http;

test('it can perform Polar API requests', function () {
    Http::fake([
        'polaraccesslink.com/*' => Http::response(['data' => 'test'], 200),
    ]);

    $response = PolarClient::get('exercises');

    $this->assertEquals(200, $response->status());
});
