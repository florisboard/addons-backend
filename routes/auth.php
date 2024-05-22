<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\GithubController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->middleware('auth')
        ->name('logout');

    Route::get('github/redirect', [GithubController::class, 'redirect'])
        ->name('github.redirect');

    Route::get('github/callback', [GithubController::class, 'callback'])
        ->name('github.callback');
});
