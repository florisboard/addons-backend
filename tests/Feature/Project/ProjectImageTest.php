<?php

use App\Models\Project;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses()->group('ProjectImage');

describe('Store', function () {
    test('users can store project image', function () {
        Sanctum::actingAs($user = User::factory()->create());
        Project::factory()->for($user)->create();

        $this->getJson(route('projects.index'))
            ->assertOk()
            ->assertJsonFragment(['is_active' => true]);
    });

    test('users can get their un active projects', function () {
        Sanctum::actingAs($user = User::factory()->create());
        Project::factory()->for($user)->forCategory()->create(['is_active' => false]);

        $this->getJson(route('projects.index', ['filter' => ['user_id' => $user->id]]))
            ->assertOk()
            ->assertJsonFragment(['is_active' => false]);
    });
});
