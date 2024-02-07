<?php

use App\Http\Controllers\FileUploadController;
use App\Models\User;
use Illuminate\Http\UploadedFile;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    actingAs(User::factory()->create());
    Storage::fake();

    $this->freezeTime();
    Str::createRandomStringsUsing(static fn (): string => 'random');
});

test('users can upload', function () {
    $file = UploadedFile::fake()->image('image.png')->size(5);

    $path = App::make(FileUploadController::class)->generatePath();
    $expectedFilePath = "$path/random.png";

    $this->postJson(route('uploads.process'), ['file' => $file])
        ->assertOk()
        ->assertSee($expectedFilePath);

    Storage::assertExists($expectedFilePath);
});

test('users should send a file', function () {
    $this->postJson(route('uploads.process'))
        ->assertUnprocessable();
});

test('users cannot send a file bigger than 512KB', function () {
    $file = UploadedFile::fake()->image('image.png')->size(600);

    $this->postJson(route('uploads.process'), ['file' => $file])
        ->assertUnprocessable();
});
