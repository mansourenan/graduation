<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DetectionController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/complete-profile', [AuthController::class, 'completeProfile']);


    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar']);
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);
    Route::post('/profile/notifications', [ProfileController::class, 'toggleNotifications']);


    Route::get('/settings', [SettingsController::class, 'index']);
    Route::get('/settings/about', [SettingsController::class, 'about']);
    Route::get('/settings/general', [SettingsController::class, 'general']);


    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/verify-code', [PasswordResetController::class, 'verifyCode']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);


Route::get('/health', function () {
    return response()->json(['status' => 'OK', 'message' => 'API is running']);
});

Route::post('/test-email', function () {
    Mail::raw('اختبار البريد', function ($message) {
        $message->to('test@example.com')->subject('اختبار');
    });
    return response()->json(['message' => 'Email sent successfully']);
});

Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});

// Social OAuth Routes
Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
Route::get('/auth/facebook/redirect', [AuthController::class, 'redirectToFacebook']);
Route::get('/auth/facebook/callback', [AuthController::class, 'handleFacebookCallback']);
Route::get('/auth/twitter/redirect', [AuthController::class, 'redirectToTwitter']);
Route::get('/auth/twitter/callback', [AuthController::class, 'handleTwitterCallback']);

Route::get('/detection/status', [DetectionController::class, 'status']);
