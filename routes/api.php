<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\TrainerController;
use App\Http\Controllers\Api\AdminController;
use GuzzleHttp\Middleware;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API Routes

// Public Routes
Route::post('/login', [UserController::class, 'login'])->name('user.login');

// Protected Routes
Route::group([
    'middleware' => 'auth:api',
], function () {

    // Common Auth Routes
    Route::post('/logout', [UserController::class, 'logout'])->name('user.logout'); // Logout user
    Route::post('/refresh-token', [UserController::class, 'refreshToken'])->name('token.refresh'); // Refresh token

    // Common User Routes
    Route::get('/user/{userId}', [UserController::class, 'getUser'])->name('user.get'); // Get user
    Route::put('/user/{userId}', [UserController::class, 'updateUser'])->name('user.update'); // Update user
    Route::delete('/user/{userId}', [UserController::class, 'deleteUser'])->name('user.delete'); // Delete user

    // Admin Routes (only for admins)
    Route::group(
        ['middleware' => 'admin'],
        function () {
            Route::post('/register', [UserController::class, 'register'])->name('user.register'); // Create and Register new user
            Route::get('/admin/{userId}', [AdminController::class, 'getAdmin'])->name('admin.get'); // Get admin
            Route::get('/admins', [AdminController::class, 'getAllAdmins'])->name('admin.get.all'); // Get all admins
            Route::get('/members', [MemberController::class, 'getAllMembers'])->name('member.get.all'); // Get all members
            Route::get('/trainers', [TrainerController::class, 'getAllTrainers'])->name('trainer.get.all'); // Get all trainers

            // automatic routes (backup routes) 
            Route::post('/admin', [AdminController::class, 'createAdmin'])->name('admin.create'); // Create new admin
            Route::post('/member', [MemberController::class, 'createMember'])->name('member.create'); // Create new member
            Route::post('/trainer', [TrainerController::class, 'createTrainer'])->name('trainer.create'); // Create new trainer
            Route::delete('/admin/{userId}', [AdminController::class, 'deleteAdmin'])->name('admin.delete'); // Delete admin
        }
    );

    // Member Routes (for members and admins)
    Route::group(
        ['middleware' => ['member', 'admin']],
        function () {
            Route::get('/member/{userId}', [MemberController::class, 'getMember'])->name('member.get'); // Get member
            Route::put('/member/{userId}', [MemberController::class, 'updateMember'])->name('member.update'); // Update member

            // automatic routes (backup routes) 
            Route::delete('/member/{userId}', [MemberController::class, 'deleteMember'])->name('member.delete'); // Delete member
        }
    );

    // Trainer Routes (for trainers and admins)
    Route::group(
        ['middleware' => ['member', 'admin']],
        function () {
            Route::get('/trainer/{userId}', [TrainerController::class, 'getTrainer'])->name('trainer.get'); // Get trainer
            Route::put('/trainer/{userId}', [TrainerController::class, 'updateTrainer'])->name('trainer.update'); // Update trainer

            // automatic routes (backup routes) 
            Route::delete('/trainer/{userId}', [TrainerController::class, 'deleteTrainer'])->name('trainer.delete'); // Delete trainer
        }
    );
});
