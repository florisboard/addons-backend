<?php

use App\Enums\StatusEnum;
use App\Models\Project;
use App\Models\Release;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;

uses()->group('Release');

describe('Index', function () {
    test('users can get releases', function () {
        Release::factory()->create();

        $this->getJson(route('reviews.index'))
            ->assertOk();
    });
});

describe('Download', function () {
    test('users can download a release', function () {
        Storage::fake();

        $release = Release::factory()->create(['status' => StatusEnum::Approved]);

        $file = UploadedFile::fake()->create('file.flex', 1);
        $release->addMedia($file)->toMediaCollection('file');

        $this->getJson(route('releases.download', [$release]))
            ->assertOk();

        $this->assertDatabaseHas(Release::class, [
            'id' => $release->id,
            'downloads_count' => $release->downloads_count + 1,
        ]);
    });
});

describe('Create', function () {
    test('users can create a release', function () {
        Sanctum::actingAs($user = User::factory()->create());
        $project = Project::factory()
            ->has(Release::factory(['version_name' => '1.0.0']))
            ->for($user)
            ->create();

        $data = [
            ...Release::factory()->make()->toArray(),
            'version_name' => '2.0.0',
            'file_path' => createUploadedFile('file.flex'),
        ];

        $this->postJson(route('projects.releases.store', [$project]), $data)
            ->assertCreated();
    });

    test('users cannot create a release from another project', function () {
        Sanctum::actingAs(User::factory()->create());
        $project = Project::factory()->create();

        $this->postJson(route('projects.releases.store', [$project]))
            ->assertForbidden();
    });
});
