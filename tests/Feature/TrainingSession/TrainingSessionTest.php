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
        ->get(route('training-sessions.show', $session));

    $response->assertStatus(403);
});

test('test user can view own session', function () {
    $user = User::factory()->create();

    $session = TrainingSession::factory()
        ->for($user)
        ->create();

    $response = $this->actingAs($user)
        ->get(route('training-sessions.show', $session));

    $response->assertStatus(200);

});

test('unauthenticated user cannot update training session', function () {
    $user = User::factory()->create();

    $session = TrainingSession::factory()
        ->for($user)
        ->create();

    $response = $this->patch(
        route('training-sessions.update', $session),
        [
            'rating' => 5,
            'notes' => 'Great session!',
        ]
    );

    $response->assertRedirect(route('login'));
});

test('user cannot update another users training session', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $session = TrainingSession::factory()
        ->for($user1)
        ->create();

    $response = $this->actingAs($user2)
        ->patch(
            route('training-sessions.update', $session),
            [
                'rating' => 5,
                'notes' => 'Great session!',
            ]
        );

    $response->assertStatus(403);
});

test('user can update their own training session', function () {
    $user = User::factory()->create();

    $session = TrainingSession::factory()
        ->for($user)
        ->create([
            'rating' => null,
            'notes' => null,
        ]);

    $response = $this->actingAs($user)
        ->patch(
            route('training-sessions.update', $session),
            [
                'rating' => 5,
                'notes' => 'Excellent training session!',
            ]
        );

    $response->assertStatus(200);

    $session->refresh();

    expect($session->rating)->toBe(5);
    expect($session->notes)->toBe('Excellent training session!');
});
