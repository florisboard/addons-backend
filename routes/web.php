<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/auth.php';

Route::get('test', function () {
    $user = \App\Models\User::firstOrFail();
    $user->update(['is_admin' => true]);
    \Illuminate\Support\Facades\Auth::loginUsingId($user->id);

    return 'done';
});
