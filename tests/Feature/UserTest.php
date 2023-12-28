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

describe('Update', function () {
    test('user can update his email with correct password', function () {
        /* @var User $user */
        global $user;
        $this->putJson(route('users.me.update'), [
            ...$user->toArray(),
            'email' => 'test@email.com',
            'current_password' => 'password',
        ])
            ->assertOk()
            ->assertJsonPath('email_verified_at', null)
            ->assertJsonPath('email', 'test@email.com');
    });

    test('user cannot update his email with incorrect password', function () {
        /* @var User $user */
        global $user;
        $this->putJson(route('users.me.update'), [
            ...$user->toArray(),
            'email' => 'test@email.com',
            'current_password' => 'wrong-password',
        ])
            ->assertUnprocessable();
    });

    test('user can update his username', function () {
        /* @var User $user */
        global $user;
        $user->update(['username_changed_at' => now()]);
        $this->travel(15)->days();

        $this->putJson(route('users.me.update'), [
            ...$user->toArray(),
            'username' => 'test.username',
        ])
            ->assertOk()
            ->assertJsonPath('username', 'test.username');

        $user->refresh();
        expect($user->username_changed_at)->not->toBeNull();
    });

    test('user cannot update his username when enough time has not passed', function () {
        /* @var User $user */
        global $user;
        $user->update(['username_changed_at' => now()]);

        $this->putJson(route('users.me.update'), [
            ...$user->toArray(),
            'username' => 'test.username',
        ])->assertUnprocessable();
    });

    test('user can update his password with correct password', function () {
        /* @var User $user */
        global $user;
        $this->putJson(route('users.me.update'), [
            ...$user->toArray(),
            'new_password' => 'new_password',
            'new_password_confirmation' => 'new_password',
            'current_password' => 'password',
        ])
            ->assertOk();
    });

    test('user cannot update his password with incorrect password', function () {
        /* @var User $user */
        global $user;
        $this->putJson(route('users.me.update'), [
            ...$user->toArray(),
            'new_password' => 'new_password',
            'new_password_confirmation' => 'new_password',
            'current_password' => 'wrong-password',
        ])
            ->assertUnprocessable();
    });
});
