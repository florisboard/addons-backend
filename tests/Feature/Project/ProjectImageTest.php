<?php

use App\Models\Media;
use App\Models\Project;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses()->group('ProjectImage');

describe('Store', function () {
    test('users can create project image', function () {
        Sanctum::actingAs($user = User::factory()->create());
        $project = Project::factory()->for($user)->create();
        $data = ['image' => createUploadedFile()];

        $this->postJson(route('projects.image.store', [$project]), $data)
            ->assertOk();
    });
});
