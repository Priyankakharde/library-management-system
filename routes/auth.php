<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| This file contains routes for login / logout / register / password reset.
| If you have a scaffolding (laravel/ui, breeze, fortify) you can keep
| the scaffolding's routes. Otherwise Auth::routes() will add the
| standard login/register/password routes if the package is present.
|
*/

// If you are using laravel/ui or the classic auth scaffold:
try {
    // If Auth::routes() exists (most typical setups)
    Auth::routes();
} catch (Throwable $e) {
    // If Auth::routes() is not available (rare), define minimal routes as fallback.
    // IMPORTANT: adjust controller class names if you use other controllers.
    Route::get('login',  [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
    Route::post('logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);

    // password reset (if controllers are present)
    if (class_exists(\App\Http\Controllers\Auth\PasswordResetLinkController::class)) {
        Route::get('forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');
        Route::get('reset-password/{token}', [\App\Http\Controllers\Auth\NewPasswordController::class, 'create'])->name('password.reset');
        Route::post('reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'store'])->name('password.update');
    }
}
