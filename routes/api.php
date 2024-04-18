<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\TrainerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API Routes

// Public Routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// Protected Routes
Route::group([
    'middleware' => 'auth:api'
], function () {

    // Common routes accessible by all authenticated users
    Route::get('/user', [UserController::class, 'profile'])->name('user.profile');
    Route::post('/refresh-token', [UserController::class, 'refreshToken'])->name('token.refresh');
    Route::post('/logout', [UserController::class, 'logout'])->name('user.logout');
    Route::put('/user', [UserController::class, 'updateUser'])->name('user.update');
    Route::delete('/user', [UserController::class, 'deleteUser'])->name('user.delete');

    // Member Routes
    Route::group(
        [
            'middleware' => 'member'
        ],
        function () {
            //  Route::post('/member/{userId}', [MemberController::class, 'createMember'])->name('member.create');
            Route::put('/member/{userId}', [MemberController::class, 'updateMember'])->name('member.update');
        }
    );

    // Trainer Routes
    Route::group(
        [
            'middleware' => 'trainer'
        ],
        function () {
            Route::post('/trainer/{userId}', [TrainerController::class, 'createTrainer'])->name('trainer.create');
            Route::put('/trainer/{userId}', [TrainerController::class, 'updateTrainer'])->name('trainer.update');
        }
    );
});
