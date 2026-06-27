<?php

use App\Http\Controllers\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\UserRegistration;
use App\Http\Controllers\Auth\GuestRegistration;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;



Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store'])->name('reg');

       Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);


    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});



Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm/password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    // Route::post('store-password/', [ConfirmablePasswordController::class, 'store'])->name('password.store');

    Route::post('user/password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('admin/password', [PasswordController::class, 'ad_update'])->name('admin.password.update');
    Route::get('user/password/', [PasswordController::class, 'show'])->name('password.show');
    Route::get('admin/password/', [PasswordController::class, 'ad_show'])->name('admin.password.show');
    // Route::post('password', [PasswordResetLinkController::class, 'update'])->name('password.update');

    Route::post('user/transaction-password', [PasswordController::class, 'updateTransactionPassword'])->name('transaction-password.update');
    Route::post('user/transaction-password/verify', [PasswordController::class, 'verifyTransactionPassword'])->name('transaction-password.verify');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});

Route::get('admin/confirm-request', [UserRegistration::class, 'confirm_user'])->name('admin.user.confirm');
// Route::post('/user-request', [GuestRegistration::class, 'post_user'])->name('admin.user.add');
Route::post('/user-request', [UserRegistration::class, 'user_request'])->name('guest.request');


//admin authantication
Route::get('admin/login', [AdminAuthenticatedSessionController::class, 'create'])->name('admin.login');
Route::post('admin/login', [AdminAuthenticatedSessionController::class, 'store'])->name('admin.login');
