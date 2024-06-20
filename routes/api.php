<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\Domain\DomainController;
use App\Http\Controllers\Domain\DomainVerifyController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Project\ProjectImageController;
use App\Http\Controllers\Project\ProjectReportController;
use App\Http\Controllers\Project\ScreenshotController;
use App\Http\Controllers\ReleaseController;
use App\Http\Controllers\Review\ReviewController;
use App\Http\Controllers\Review\ReviewReportController;
use App\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users/me', [UserController::class, 'me'])->name('users.me');
    Route::put('users/me', [UserController::class, 'update'])->name('users.me.update');
    Route::post('users/me/delete', [UserController::class, 'destroy'])->name('users.me.destroy');
    Route::post('uploads/process', FileUploadController::class)->name('uploads.process')->middleware('throttle:fileUpload');

    Route::apiResource('projects.reports', ProjectReportController::class)->only('store');
    Route::apiResource('reviews.reports', ReviewReportController::class)->only('store');

    Route::apiResource('domains', DomainController::class)->only(['index', 'store', 'destroy']);
    Route::apiResource('domains.verify', DomainVerifyController::class)->only(['store'])->middleware('throttle:verifyDomain');
});

Route::get('home', HomeController::class)->name('home');

Route::apiResource('projects', ProjectController::class);
Route::apiSingleton('projects.image', ProjectImageController::class)->creatable()->only(['store', 'destroy']);
Route::apiResource('projects.screenshots', ScreenshotController::class)->only(['store', 'destroy']);

Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('collections', CollectionController::class);
Route::apiResource('users', UserController::class)->only(['index', 'show']);

Route::apiResource('projects.releases', ReleaseController::class)->only('store');
Route::apiResource('releases', ReleaseController::class)->only(['index', 'update']);
Route::get('releases/{release}/download', [ReleaseController::class, 'download'])->name('releases.download');

Route::apiResource('projects.reviews', ReviewController::class)->shallow()->only('store');
Route::apiResource('reviews', ReviewController::class)->except('store');
