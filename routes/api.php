<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;

Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('projects', ProjectController::class);
Route::apiResource('collections', CollectionController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users/me', [UserController::class, 'me'])->name('users.me');
    Route::post('users/me/delete', [UserController::class, 'destroy'])->name('users.me.destroy');
});
