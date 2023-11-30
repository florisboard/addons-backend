<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;

Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('projects', ProjectController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users/me', [UserController::class, 'me']);
});
