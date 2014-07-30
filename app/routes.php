<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// Check if we should show the install screen, otherwise redirect to tasks.
Route::get('', function()
{
    return Redirect::to('tasks');
});

// Hook in all our controllers.
Route::controller('backup', 'Controllers\Backup');

Route::controller('config', 'Controllers\Config');

Route::controller('install', 'Controllers\Install');

Route::controller('tasks', 'Controllers\Task');

Route::controller('users', 'Controllers\User');
