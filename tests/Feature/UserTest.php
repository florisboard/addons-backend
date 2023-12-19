<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses()->group('User');

beforeEach(function () {
    global $user;
    $user = User::factory()->create();
    Sanctum::actingAs($user);
});

describe('Me', function () {
    test('user can get his info', function () {
        $this->getJson(route('users.me'))->assertOk();
    });
});

describe('Destroy', function () {
    test('user can delete his account with correct password', function () {
        /* @var User $user */
        global $user;
        $this->postJson(route('users.me.destroy'), [
            'password' => 'password',
        ])
            ->assertOk();

        $this->assertModelMissing($user);
    });

    test('user cannot delete his account with wrong password', function () {
        /* @var User $user */
        global $user;
        $this->postJson(route('users.me.destroy'), [
            'password' => 'wrong-password',
        ])
            ->assertUnprocessable();
        $this->assertModelExists($user);
    });
});
