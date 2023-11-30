<?php

test('new users can register', function () {
    $response = $this->post(route('register'), [
        'username' => 'testing',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertNoContent();
});
