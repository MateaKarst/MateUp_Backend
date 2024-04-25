<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\TrainerController;
use App\Http\Controllers\Api\AdminController;

// API Routes

// Public Routes
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication Routes
Route::post('/login', [UserController::class, 'login'])->name('user.login'); // Login user

// Protected Routes
Route::group([
    'middleware' => 'auth:api',
], function () {

    // COMMON ROUTES
    // Common Authentication Routes
    Route::post('/logout', [UserController::class, 'logout'])->name('user.logout'); // Logout user
    Route::post('/refresh-token', [UserController::class, 'refreshToken'])->name('token.refresh'); // Refresh token

    // Common User Routes
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'getUser'])->name('user.get'); // Get user
        Route::put('/', [UserController::class, 'updateUser'])->name('user.update'); // Update user
        Route::delete('/', [UserController::class, 'deleteUser'])->name('user.delete'); // Delete user
    });

    // ADMIN ROUTES (Access to all routes)
    Route::middleware(['admin'])->group(function () {

        // Authentication Routes (only for admins to use)
        Route::post('/register', [UserController::class, 'register'])->name('user.register'); // Register new user

        // Admin Routes
        Route::prefix('admin')->group(function () {
            Route::get('/', [AdminController::class, 'getAdmin'])->name('admin.get'); // Get admin
            Route::get('/all', [AdminController::class, 'getAllAdmins'])->name('admin.get.all'); // Get all admins

            // Automatic Routes (not to be used - Backup routes)
            Route::post('/', [AdminController::class, 'createAdmin'])->name('admin.create'); // Create admin
            Route::delete('/', [AdminController::class, 'deleteAdmin'])->name('admin.delete'); // Delete admin
        });

        // USER ROUTES
        // User Routes (only for admins)
        Route::prefix('user')->group(function () {
            Route::get('/all', [AdminController::class, 'getAllUsers'])->name('admin.user.get.all'); // Get all users

            // To cRUD other users
            Route::get('/{id}', [AdminController::class, 'getUser'])->name('admin.user.get'); // Get user
            Route::put('/{id}', [AdminController::class, 'updateUser'])->name('admin.user.update'); // Update user
            Route::delete('/{id}', [AdminController::class, 'deleteUser'])->name('admin.user.delete'); // Delete user
        });

        // MEMBER ROUTES
        // Member Routes (accessible to members and admins)
        Route::middleware(['member'])->group(function () {
            // Member Routes
            Route::prefix('member')->group(function () {
                Route::get('/', [MemberController::class, 'getMember'])->name('member.get'); // Get member
                Route::put('/', [MemberController::class, 'updateMember'])->name('member.update'); // Update member
            });
        });

        // Member Routes (only for admins)
        Route::prefix('member')->group(function () {
            Route::get('/all', [AdminController::class, 'getAllMembers'])->name('admin.member.get.all'); // Get all members

            // To cRUD other members
            Route::get('/{id}', [AdminController::class, 'getMember'])->name('admin.member.get'); // Get member
            Route::put('/{id}', [AdminController::class, 'updateMember'])->name('admin.member.update'); // Update member
            Route::delete('/{id}', [AdminController::class, 'deleteMember'])->name('admin.member.delete'); // Delete member

            // Automatic Routes (not to be used - Backup routes)
            Route::post('/', [AdminController::class, 'createMember'])->name('admin.member.create'); // Create member
            Route::delete('/', [MemberController::class, 'deleteMember'])->name('admin.member.delete'); // Delete member
        });

        // TRAINER ROUTES
        // Trainer Routes (accessible to trainers and admins)
        Route::middleware(['trainer'])->group(function () {
            // Trainer Routes
            Route::prefix('trainer')->group(function () {
                Route::get('/', [TrainerController::class, 'getTrainer'])->name('trainer.get'); // Get trainer
                Route::put('/', [TrainerController::class, 'updateTrainer'])->name('trainer.update'); // Update trainer
            });
        });

        // Trainer Routes (only for admins)
        Route::prefix('trainer')->group(function () {
            Route::post('/', [AdminController::class, 'createTrainer'])->name('admin.trainer.create'); // Create trainer
            Route::get('/all', [AdminController::class, 'getAllTrainers'])->name('admin.trainer.get.all'); // Get all trainers

            // To cRUD other trainers
            Route::get('/{id}', [AdminController::class, 'getTrainer'])->name('admin.trainer.get'); // Get trainer
            Route::put('/{id}', [AdminController::class, 'updateTrainer'])->name('admin.trainer.update'); // Update trainer
            Route::delete('/{id}', [AdminController::class, 'deleteTrainer'])->name('admin.trainer.delete'); // Delete trainer

            // Automatic Routes (not to be used - Backup routes)
            Route::delete('/', [TrainerController::class, 'deleteTrainer'])->name('admin.trainer.delete'); // Delete trainer
        });
    });
});
