<?php

use App\Models\TrainingSession;
use App\Models\User;

test('test user cannot view other users session', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $session = TrainingSession::factory()
        ->for($user1)
        ->create();

    $response = $this->actingAs($user2)
        ->get(route('sessions.session', $session));

    $response->assertStatus(403);
});

test('test user can view own session', function () {
    $user = User::factory()->create();

    $session = TrainingSession::factory()
        ->for($user)
        ->create();

    $response = $this->actingAs($user)
        ->get(route('sessions.session', $session));

    $response->assertStatus(200);

});
