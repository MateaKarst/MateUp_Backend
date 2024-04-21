<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\TrainerController;
use App\Http\Controllers\Api\AdminController;
use GuzzleHttp\Middleware;

// API Routes

// Public Routes
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [UserController::class, 'login'])->name('user.login'); // Login user

// Protected Routes
Route::group([
    'middleware' => 'auth:api',
], function () {

    // Common Auth Routes
    Route::post('/logout', [UserController::class, 'logout'])->name('user.logout'); // Logout user
    Route::post('/refresh-token', [UserController::class, 'refreshToken'])->name('token.refresh'); // Refresh token

    // Common User Routes
    Route::prefix('user')->group(function () {
        Route::get('/{userId}', [UserController::class, 'getUser'])->name('user.get'); // Get user
        Route::put('/{userId}', [UserController::class, 'updateUser'])->name('user.update'); // Update user
        Route::delete('/{userId}', [UserController::class, 'deleteUser'])->name('user.delete'); // Delete user
    });

    // Admin Routes (only for admins)
    Route::middleware(['admin'])->group(function () {

        // Authentication Routes
        Route::post('/register', [UserController::class, 'register'])->name('user.register'); // Register new user

        // Admin Routes
        Route::prefix('admin')->group(function () {
            Route::get('/{userId}', [AdminController::class, 'getAdmin'])->name('admin.get'); // Get admin
            Route::get('/all', [AdminController::class, 'getAllAdmins'])->name('admin.get.all'); // Get all admins

            // Automatic Routes
            Route::post('/', [AdminController::class, 'createAdmin'])->name('admin.create'); // Create admin
            Route::delete('/{userId}', [AdminController::class, 'deleteAdmin'])->name('admin.delete'); // Delete admin
        });

        // Member and Trainer Routes (only for admins)
        Route::prefix('member')->group(function () {
            Route::get('/all', [AdminController::class, 'getAllMembers'])->name('admin.member.get.all'); // Get all members

            // Automatic Routes
            Route::post('/', [AdminController::class, 'createMember'])->name('admin.member.create'); // Create member
        });

        Route::prefix('trainer')->group(function () {
            Route::get('/all', [AdminController::class, 'getAllTrainers'])->name('admin.trainer.get.all'); // Get all trainers

            // Automatic Routes
            Route::post('/', [AdminController::class, 'createTrainer'])->name('admin.trainer.create'); // Create trainer
        });

        // Member Routes (accessible to members and admins)
        Route::middleware(['member'])->group(function () {
            // Member Routes
            Route::prefix('member')->group(function () {
                Route::get('/{userId}', [MemberController::class, 'getMember'])->name('member.get'); // Get member
                Route::put('/{userId}', [MemberController::class, 'updateMember'])->name('member.update'); // Update member

                // Automatic Routes
                Route::delete('/{userId}', [MemberController::class, 'deleteMember'])->name('member.delete'); // Delete member
            });
        });

        // Trainer Routes (accessible to trainers and admins)
        Route::middleware(['trainer'])->group(function () {
            // Trainer Routes
            Route::prefix('trainer')->group(function () {
                Route::get('/{userId}', [TrainerController::class, 'getTrainer'])->name('trainer.get'); // Get trainer
                Route::put('/{userId}', [TrainerController::class, 'updateTrainer'])->name('trainer.update'); // Update trainer

                // Automatic Routes
                Route::delete('/{userId}', [TrainerController::class, 'deleteTrainer'])->name('trainer.delete'); // Delete trainer
            });
        });
    });
});
