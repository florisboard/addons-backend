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

use App\Models\Project;

require __DIR__.'/auth.php';

Route::get('/test', function () {
    dd(Project::find(20)->latestApprovedRelease);
});
