<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class)
    ->only(['index', 'store', 'show', 'update', 'destroy'])
    ->names('api.user');

Route::post('users/{user}/images', [UserController::class, 'addImages'])
    ->name('api.user.images');

Route::delete('images/{image}', [UserController::class, 'deleteImage'])
    ->name('api.image.delete');

Route::get('/seed', function () {
    \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => \Database\Seeders\UserSeeder::class, '--force' => true]);
    return response()->json(['message' => 'Seed completed! Added 15 contacts.']);
});
