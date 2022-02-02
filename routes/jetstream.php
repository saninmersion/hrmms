<?php

use Illuminate\Support\Facades\Route;
use Laravel\Jetstream\Http\Controllers\Inertia\ProfilePhotoController;
use Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController;

Route::middleware(config('jetstream.middleware', ['admin']))->group(
    function () {
        // User & Profile...
        Route::get('/user/profile', [UserProfileController::class, 'show'])->name('profile.show');

        Route::delete('/user/profile-photo', [ProfilePhotoController::class, 'destroy'])->name(
            'current-user-photo.destroy'
        );
    }
);
