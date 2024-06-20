<?php

use App\Models\Project;
use App\Models\Report;
use App\Models\Review;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses()->group('ReviewReport');

describe('Create', function () {
    test('users can report a review', function () {
        Sanctum::actingAs(User::factory()->create());
        $review = Review::factory()->create();
        $data = Report::factory()->make()->toArray();

        $this->postJson(route('reviews.reports.store', $review), $data)
            ->assertCreated();
    });
});
