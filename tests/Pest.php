<?php

use App\Http\Controllers\FileUploadController;
use Illuminate\Http\UploadedFile;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(TestCase::class, FastRefreshDatabase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function createCustomUploadedFile(UploadedFile $uploadedFile): bool|string
{
    Storage::fake('local');
    config()->set('filesystems.disks.local', [
        'driver' => 'local',
        'root' => Storage::disk('local')->path(''),
    ]);

    Str::createRandomStringsUsing(static fn (): string => 'random');
    $path = App::make(FileUploadController::class)->generatePath($uploadedFile->getClientOriginalExtension());

    $exploded = explode('/', $path);
    $basePath = implode('/', array_slice($exploded, 0, -1));

    return Storage::putFileAs($basePath, $uploadedFile, end($exploded));
}

function createUploadedImage($fileName = 'test.png'): bool|string
{
    return createCustomUploadedFile(UploadedFile::fake()->image($fileName));
}

function createUploadedFile(string $fileName): bool|string
{
    return createCustomUploadedFile(UploadedFile::fake()->create($fileName));
}
