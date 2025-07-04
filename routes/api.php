<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/complete-profile', [AuthController::class, 'completeProfile']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/verify-code', [PasswordResetController::class, 'verifyCode']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);


Route::middleware('auth:sanctum')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar']);
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);

    // Settings Routes
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::get('/settings/about', [SettingsController::class, 'about']);
    Route::get('/settings/general', [SettingsController::class, 'general']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});

Mail::raw('اختبار البريد', function ($message) {
    $message->to('test@example.com')->subject('اختبار');
});
