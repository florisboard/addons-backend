<?php

use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses()->group('Project');

describe('Index', function () {
    test('users can get projects', function () {
        Project::factory()->forUser()->forCategory()->create();

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

describe('Show', function () {
    test('users can get a project', function () {
        $project = Project::factory()->forUser()->forCategory()->create();

        $this->getJson(route('projects.show', [$project]))
            ->assertOk();
    });
});

describe('Delete', function () {
    test('users can delete their project', function () {
        Sanctum::actingAs($user = User::factory()->create());
        $project = Project::factory()->for($user)->forCategory()->create();

        $this->deleteJson(route('projects.destroy', [$project]))
            ->assertOk();
    });

    test('users cannot delete other project', function () {
        Sanctum::actingAs(User::factory()->create());
        $project = Project::factory()->forUser()->forCategory()->create();

        $this->deleteJson(route('projects.destroy', [$project]))
            ->assertForbidden();
    });
});

describe('Update', function () {
    test('users can update their project', function () {
        Sanctum::actingAs($user = User::factory()->create());
        $project = Project::factory()->for($user)->forCategory()->create();
        $data = $project->toArray();

        $this->putJson(route('projects.update', [$project]), $data)
            ->assertOk();
    });

    test('users cannot delete other project', function () {
        Sanctum::actingAs(User::factory()->create());
        $project = Project::factory()->forUser()->forCategory()->create();

        $this->putJson(route('projects.update', [$project]))
            ->assertForbidden();
    });

    test('maintainers can update their project', function () {
        [$maintainer, $user] = User::factory(2)->create();
        Sanctum::actingAs($maintainer);
        $project = Project::factory()->for($user)->forCategory()->create();
        $data = $project->toArray();

        $project->maintainers()->attach($maintainer->id);

        $this->putJson(route('projects.update', [$project]), $data)
            ->assertOk();
    });
});

describe('Create', function () {
    beforeEach(function () {
        Category::factory()->create();
    });

    test('verified users can create a project', function () {
        $users = User::factory(4)->create();
        Sanctum::actingAs($users[0]);
        $data = [
            ...Project::factory()->make()->toArray(),
            'category_id' => Category::first()->id,
            'maintainers' => $users->splice(1)->pluck('id')->flatten()->toArray(),
        ];

        $this->postJson(route('projects.store'), $data)
            ->assertCreated();
    });

    test('current user cannot be in the maintainers list', function () {
        Sanctum::actingAs($user = User::factory()->create());
        $data = [
            ...Project::factory()->make()->toArray(),
            'category_id' => Category::first()->id,
            'maintainers' => [$user->id],
        ];

        $this->postJson(route('projects.store'), $data)
            ->assertJsonValidationErrorFor('maintainers.0');
    });

    test('unverified users cannot create a project', function () {
        Sanctum::actingAs(User::factory()->unverified()->create());

        $this->postJson(route('projects.store'))
            ->assertForbidden();
    });
});
