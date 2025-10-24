<?php

use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\TaskController;
use App\Models\Task;


Route::controller(SessionController::class)->prefix('auth')->group(function () {
    Route::post('login', 'create');
    Route::delete('logout', 'destroy');
});

Route::get('/', fn() => 'asfasfasf')->middleware('auth:api');
Route::controller(TaskController::class)
    ->prefix('task')
    ->name('task.')
    ->middleware('auth:api')
    ->group(function () {
        Route::post('/', 'create')->can('create', Task::class)->name('create');
        Route::get('/', 'index')->name('index');
        Route::get('/{task}', 'show')->can('view', 'task')->name('show');
        Route::patch('/{task}', 'update')->can('update', 'task')->name('update');
    });
