<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\TrainerController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ActivityController;

// API Routes

// -------------- USER ROUTES : MEMBER, TRAINER, ADMIN -------------- 
// ------- PUBLIC ROUTES ------- 
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [UserController::class, 'login'])->name('user.login'); // Login user

Route::middleware(['customAuth'])->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('user.logout'); // Logout user
    Route::post('/register', [UserController::class, 'register'])->name('user.register'); // Register new user
    Route::post('/', [TrainerController::class, 'createTrainer'])->name('admin.trainer.create'); // Create trainer
});



// Route::post('/login', [UserController::class, 'login'])->name('user.login'); // Login user
// Route::post('/register', [UserController::class, 'register'])->name('user.register'); // Register new user
// Route::post('/', [TrainerController::class, 'createTrainer'])->name('admin.trainer.create'); // Create trainer
// Route::post('/logout', [UserController::class, 'logout'])->name('user.logout'); // Logout user

// GET ALL ROUTES
Route::get('/users', [UserController::class, 'getAllUsers'])->name('users.get'); // Get all users
Route::get('/members', [MemberController::class, 'getAllMembers'])->name('members.get'); // Get all members
Route::get('/trainers', [TrainerController::class, 'getAllTrainers'])->name('trainers.get'); // Get all trainers
Route::get('/admins', [AdminController::class, 'getAllAdmins'])->name('admins.get'); // Get all admins

// ------- PROTECTED ROUTES -------
Route::group([
    'middleware' => 'auth:api',
], function () {

    // ----- USER ROUTES ----- 
    // User Routes (for all users)
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'getUser'])->name('user.get'); // Get user
        Route::put('/', [UserController::class, 'updateUser'])->name('user.update'); // Update user
        Route::delete('/', [UserController::class, 'deleteUser'])->name('user.delete'); // Delete user
        Route::post('/refresh-token', [UserController::class, 'refreshToken'])->name('token.refresh'); // Refresh token
    });
    // User Routes (only for admins)
    Route::middleware(['admin'])->prefix('user')->group(function () {
        Route::get('/{id}', [UserController::class, 'getUser'])->name('user.get'); // Get another user
        Route::delete('/{id}', [UserController::class, 'deleteUser'])->name('user.delete'); // Delete user       
    });

    // ----- ADMIN ROUTES ----- 
    // Admin Routes (only for admins)
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'getAdmin'])->name('admin.get'); // Get admin
    });

    // ----- MEMBER ROUTES -----
    // Member Routes (only for members)
    Route::middleware(['member'])->prefix('member')->group(function () {
        Route::get('/', [MemberController::class, 'getMember'])->name('member.get'); // Get member
        Route::put('/', [MemberController::class, 'updateMember'])->name('member.update'); // Update member
    });
    // Member Routes (only for admins)
    Route::middleware(['admin'])->prefix('member')->group(function () {
        Route::get('/{id}', [MemberController::class, 'getMember'])->name('member.get'); // Get member
    });

    // ----- TRAINER ROUTES -----
    // Trainer Routes (only for trainers)
    Route::middleware(['trainer'])->prefix('trainer')->group(function () {
        Route::get('/', [TrainerController::class, 'getTrainer'])->name('trainer.get'); // Get trainer
        Route::put('/', [TrainerController::class, 'updateTrainer'])->name('trainer.update'); // Update trainer
    });
    // Trainer Routes (only for admins)
    Route::middleware(['admin'])->prefix('trainer')->group(function () {
        Route::get('/{id}', [TrainerController::class, 'getTrainer'])->name('admin.trainer.get'); // Get trainer
    });
});

// // -------------- CALENDAR ACTIVITY ROUTES : MEMBER, ADMIN --------------- 
// // ------- PROTECTED ROUTES -------
// Route::group([
//     'middleware' => 'auth:api',
// ], function () {
//     // Member Routes (only for members)
//     Route::middleware(['member'])->prefix('activity')->group(function () {
//         Route::post('/', [ActivityController::class, 'createActivity'])->name('member.activity.index'); // Create activity
//         Route::get('/', [ActivityController::class, 'getActivity'])->name('member.activity.get'); // Get activity
//         Route::get('/all', [ActivityController::class, 'getAllActivities'])->name('member.activity.get.all'); // Get all activities
//         Route::put('/', [ActivityController::class, 'updateActivity'])->name('member.activity.update'); // Update activity
//         Route::delete('/', [ActivityController::class, 'deleteActivity'])->name('member.activity.delete'); // Delete activity
//     });

//     // Admin Routes (only for admins)
//     Route::middleware(['admin'])->prefix('activity')->group(function () {
//         Route::get('/{id}', [ActivityController::class, 'getActivity'])->name('member.activity.get'); // Get activity
//         Route::get('/all/{id}', [ActivityController::class, 'getAllActivities'])->name('member.activity.get.all'); // Get all activities
//     });
// });
