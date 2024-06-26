<?php

use App\Models\Project;
use App\Models\Review;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses()->group('Review');

describe('Index', function () {
    test('users can get reviews', function () {
        Review::factory()->create();

        $this->getJson(route('reviews.index'))
            ->assertOk();
    });
});

describe('Show', function () {
    test('users can get a review', function () {
        $review = Review::factory()->create();

        $this->getJson(route('reviews.show', [$review]))
            ->assertOk();
    });
});

describe('Delete', function () {
    test('users can delete their review', function () {
        Sanctum::actingAs($user = User::factory()->create());
        $review = Review::factory()->for($user)->create();

        $this->deleteJson(route('reviews.destroy', [$review]))
            ->assertOk();
    });

    test('users cannot delete other review', function () {
        Sanctum::actingAs(User::factory()->create());
        $review = Review::factory()->create();

        $this->deleteJson(route('reviews.destroy', [$review]))
            ->assertForbidden();
    });
});

describe('Update', function () {
    test('users can update their review', function () {
        Sanctum::actingAs($user = User::factory()->create());
        $review = Review::factory()->for($user)->create();
        $data = $review->toArray();

        $this->putJson(route('reviews.update', [$review]), $data)
            ->assertOk();
    });

    test('when user updates his review it should get reviewed again', function () {
        Sanctum::actingAs($user = User::factory()->create());
        $review = Review::factory()->for($user)->create();
        $data = Review::factory()->make()->toArray();

        $this->putJson(route('reviews.update', [$review]), $data)
            ->assertOk();

        expect($review->refresh()->is_active)->toBeFalse();
    });

    test('users cannot update other review', function () {
        Sanctum::actingAs(User::factory()->create());
        $review = Review::factory()->create();

        $this->putJson(route('reviews.update', [$review]))
            ->assertForbidden();
    });
});

describe('Create', function () {
    test('users can create a review', function () {
        Sanctum::actingAs(User::factory()->create());
        $project = Project::factory()->create();
        $data = Review::factory()->for($project)->make()->toArray();

        $this->postJson(route('projects.reviews.store', [$project]), $data)
            ->assertCreated();
    });

    test('users cannot create multiple reviews for one project', function () {
        Sanctum::actingAs($user = User::factory()->create());
        $project = Project::factory()->create();
        Review::factory()->for($user)->for($project)->create();
        $data = Review::factory()->for($project)->make()->toArray();

        $this->postJson(route('projects.reviews.store', [$project]), $data)
            ->assertForbidden();
    });
});
