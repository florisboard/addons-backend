<?php

use App\Enums\StatusEnum;
use App\Models\Project;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses()->group('ProjectImage');

describe('Store', function () {
    test('users can create project image', function () {
        Sanctum::actingAs($user = User::factory()->create());
        $project = Project::factory()->for($user)->create(['status' => StatusEnum::Draft]);
        $data = ['image_path' => createUploadedImage()];

        $this->postJson(route('projects.image.store', [$project]), $data)
            ->assertOk();
    });
});
