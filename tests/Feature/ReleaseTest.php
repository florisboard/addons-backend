<?php

use App\Models\Release;
use Illuminate\Http\UploadedFile;

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

        $release = Release::factory()->create();

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
