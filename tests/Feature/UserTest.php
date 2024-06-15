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
    test('user can delete his account', function () {
        /* @var User $user */
        global $user;
        $this->postJson(route('users.me.destroy'), [
            'username' => $user->username,
        ])
            ->assertOk();

        $this->assertModelMissing($user);
    });

    test('user cannot delete his account with invalid username', function () {
        $this->postJson(route('users.me.destroy'), [
            'username' => 'random-username',
        ])->assertUnprocessable();
    });
});

describe('Update', function () {
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
})->skip();
