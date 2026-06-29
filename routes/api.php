<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class)
    ->only(['index', 'store', 'show', 'update', 'destroy'])
    ->names('api.user');
