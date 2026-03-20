<?php

// Registration is currently disabled, so these tests are commented out until it is re-enabled.
// test('registration screen can be rendered', function () {
//     $response = $this->get(route('register'));

//     $response->assertOk();
// });

// test('new users can register', function () {
//     $response = $this->post(route('register.store'), [
//         'name' => 'Test User',
//         'email' => 'test@example.com',
//         'password' => 'password',
//         'password_confirmation' => 'password',
//     ]);

//     $this->assertAuthenticated();
//     $response->assertRedirect(route('home', absolute: false));
// });