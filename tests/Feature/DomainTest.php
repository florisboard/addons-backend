<?php

use App\Models\Domain;
use App\Models\User;
use App\Services\DomainService;
use Laravel\Sanctum\Sanctum;
use Mockery\MockInterface;

uses()->group('Domain');

describe('Index', function () {
    test('users can get their domains', function () {
        User::factory()->hasDomains()->create();
        Sanctum::actingAs(User::factory()->hasDomains()->create());

        $this->getJson(route('domains.index'))
            ->assertJsonCount(2, 'data')
            ->assertOk();
    });
});

describe('Create', function () {
    test('users can create a domain', function () {
        Sanctum::actingAs(User::factory()->create());
        $data = Domain::factory()->make()->toArray();

        $this->postJson(route('domains.store'), $data)
            ->assertCreated();
    });

    test('users cannot create a domain when another unverified domain exists', function () {
        Sanctum::actingAs(User::factory()->has(Domain::factory()->state(['verified_at' => null]))->create());
        $data = Domain::factory()->make()->toArray();

        $this->postJson(route('domains.store'), $data)
            ->assertForbidden();
    });
});

describe('Delete', function () {
    test('users can delete their domains', function () {
        Sanctum::actingAs($user = User::factory()->create());
        $domain = Domain::factory()->for($user)->create();

        $this->deleteJson(route('domains.destroy', $domain))
            ->assertOk();
    });

    test('users cannot delete reserved domains', function () {
        Sanctum::actingAs($user = User::factory()->create());
        $domain = Domain::first(); // github.io domain

        $this->deleteJson(route('domains.destroy', $domain))
            ->assertForbidden();
    });

    test('users cannot delete other users domains', function () {
        Sanctum::actingAs(User::factory()->create());
        $domain = Domain::factory()->create();

        $this->deleteJson(route('domains.destroy', $domain))
            ->assertForbidden();
    });
});

describe('Verify', function () {
    test('users can verify their domain', function () {
        Sanctum::actingAs($user = User::factory()->create());
        $domain = Domain::factory()->for($user)->create();

        $this->partialMock(DomainService::class, function (MockInterface $mock) {
            $mock->shouldReceive('hasVerificationText')->once()->andReturnTrue();
        });

        $this->postJson(route('domains.verify.store', $domain))
            ->assertOk();

        $domain->refresh();
        expect($domain->verified_at)->not->toBeNull();
    });

    test('users cannot verify when verification text does not exist', function () {
        Sanctum::actingAs($user = User::factory()->create());
        $domain = Domain::factory()->for($user)->create();

        $this->partialMock(DomainService::class, function (MockInterface $mock) {
            $mock->shouldReceive('hasVerificationText')->once()->andReturnFalse();
        });

        $this->postJson(route('domains.verify.store', $domain))
            ->assertUnprocessable();
    });
});
