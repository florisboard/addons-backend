<?php

use App\Enums\StatusEnum;
use App\Models\Category;
use App\Models\Domain;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectService;
use Laravel\Sanctum\Sanctum;

uses()->group('Project');

describe('Index', function () {
    test('users can get projects', function () {
        Project::factory()->create();

        $this->getJson(route('projects.index'))
            ->assertOk()
            ->assertJsonFragment(['status' => StatusEnum::Approved]);
    });

    test('users can get their pending projects', function () {
        Sanctum::actingAs($user = User::factory()->create());
        Project::factory()->for($user)->create(['status' => StatusEnum::Pending]);

        $this->getJson(route('projects.index', ['filter' => ['user_id' => $user->id]]))
            ->assertOk()
            ->assertJsonFragment(['status' => StatusEnum::Pending]);
    });
});

describe('Show', function () {
    test('users can get a project', function () {
        $project = Project::factory()->create();

        $this->getJson(route('projects.show', [$project]))
            ->assertOk();
    });
});

describe('Delete', function () {
    test('users can delete their project', function () {
        Sanctum::actingAs($user = User::factory()->create());
        $project = Project::factory()->for($user)->create();

        $this->deleteJson(route('projects.destroy', [$project]))
            ->assertOk();
    });

    test('users cannot delete other project', function () {
        Sanctum::actingAs(User::factory()->create());
        $project = Project::factory()->create();

        $this->deleteJson(route('projects.destroy', [$project]))
            ->assertForbidden();
    });
});

describe('Update', function () {
    test('users can update their project', function () {
        Sanctum::actingAs($user = User::factory()->create());
        $project = Project::factory()->for($user)->create();
        $data = $project->toArray();

        $this->putJson(route('projects.update', [$project]), $data)
            ->assertOk();
    });

    test('users cannot delete other project', function () {
        Sanctum::actingAs(User::factory()->create());
        $project = Project::factory()->create();

        $this->putJson(route('projects.update', [$project]))
            ->assertForbidden();
    });

    test('maintainers can update their project', function () {
        [$maintainer, $user] = User::factory(2)->create();
        Sanctum::actingAs($maintainer);
        $project = Project::factory()->for($user)->create();
        $data = $project->toArray();

        $project->maintainers()->attach($maintainer->id);

        $this->putJson(route('projects.update', [$project]), $data)
            ->assertOk();
    });
});

describe('Create', function () {
    test('users can create a project', function () {
        $users = User::factory(4)->create();
        Sanctum::actingAs($currentUser = $users[0]);
        $domain = Domain::factory()->for($currentUser)->verified()->create();
        $packageName = app(ProjectService::class)->convertToPackageName('test', $domain->name);

        $data = [
            ...Project::factory()->make()->toArray(),
            'verified_domain_id' => $domain->id,
            'package_name' => $packageName,
            'category_id' => Category::first()->id,
            'maintainers' => $users->splice(1)->pluck('id')->flatten()->toArray(),
        ];

        $this->postJson(route('projects.store'), $data)
            ->assertCreated();
    });

    test('users cannot create a project when package name and verified domain id does not match', function () {
        Sanctum::actingAs($user = User::factory()->create());
        $domain = Domain::factory()->for($user)->verified()->create();
        $packageName = app(ProjectService::class)->convertToPackageName('test', 'example.com');

        $data = [
            ...Project::factory()->make()->toArray(),
            'verified_domain_id' => $domain->id,
            'package_name' => $packageName,
            'category_id' => Category::first()->id,
        ];

        $this->postJson(route('projects.store'), $data)
            ->assertJsonValidationErrorFor('package_name');
    });

    test('current user cannot be in the maintainers list', function () {
        Sanctum::actingAs($user = User::factory()->create());
        $domain = Domain::factory()->for($user)->verified()->create();
        $packageName = app(ProjectService::class)->convertToPackageName('test', $domain->name);

        $data = [
            'verified_domain_id' => $domain->id,
            'package_name' => $packageName,
            ...Project::factory()->make()->toArray(),
            'category_id' => Category::first()->id,
            'maintainers' => [$user->id],
        ];

        $this->postJson(route('projects.store'), $data)
            ->assertJsonValidationErrorFor('maintainers.0');
    });
});
