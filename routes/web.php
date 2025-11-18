<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\DefaulterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

/*
|---------------------------------------------------------------------------
| Basic routes
|---------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Auth (login/logout)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt')->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Password reset stubs
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Protected resources
Route::middleware(['auth'])->group(function () {
    Route::resource('books', BookController::class);
    Route::resource('authors', AuthorController::class)->except(['show']);
    Route::resource('students', StudentController::class)->except(['show']);
    // issue/defaulter routes (keep as before)
    Route::get('/issues', [IssueController::class, 'index'])->name('issues.index');
    Route::get('/issues/create', [IssueController::class, 'create'])->name('issues.create');
    Route::post('/issues', [IssueController::class, 'store'])->name('issues.store');
    Route::get('/issues/{id}/return', [IssueController::class, 'returnBook'])->name('issues.return');
    Route::put('/issues/{id}/return', [IssueController::class, 'returnBookSave'])->name('issues.return.save');

    Route::get('/defaulters', [DefaulterController::class, 'index'])->name('defaulters.index');
});
