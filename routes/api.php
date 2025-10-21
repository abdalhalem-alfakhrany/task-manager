<?php

use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\UserController;


Route::controller(SessionController::class)->prefix('auth')->group(function () {
    Route::post('login', 'create');
    Route::delete('logout', 'destroy');
});
