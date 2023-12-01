<?php

test('new users can register', function () {
    $this->post(route('register'), [
        'username' => 'testing',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertCreated();

    $this->assertAuthenticated();
});
