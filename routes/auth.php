<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GithubController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])
        ->middleware('auth')
        ->name('logout');

    Route::get('github/redirect', [GithubController::class, 'redirect'])
        ->name('github.redirect');

    Route::get('github/callback', [GithubController::class, 'callback'])
        ->name('github.callback');
});

Route::get('/login', [AuthController::class, 'login'])
    ->middleware('guest')
    ->name('login');
