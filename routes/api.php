<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\TrainerController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\BuddiesController;
use App\Http\Controllers\Api\GroupSessionsController;
use GuzzleHttp\Middleware;

// API Routes



// Open Routes
Route::group([
    'middleware' => 'api-session',
], function () {
    Route::prefix('user')->group(function () {
        Route::post('/login', [UserController::class, 'login']);
    });
});

// Protected Routes

// Application Routes
Route::group([
    'middleware' => 'auth:sanctum',
    'middleware' => 'api-session',
    'middleware' => 'check-token',
], function () {

    Route::get('/users', [UserController::class, 'getAllUsers'])->name('users.get'); // Get all users
    Route::get('/user/{id}', [UserController::class, 'getUser'])->name('user.get'); // Get a user

    Route::get('/members', [MemberController::class, 'getAllMembers'])->name('members.get'); // Get all members
    Route::get('/member/{id}', [MemberController::class, 'getMember'])->name('member.get'); // Get a member
    Route::get('/member/club-members/{memberId}', [MemberController::class, 'getMemberClubMembers'])->name('club-members.get'); // Get all club members of a member
    Route::get('/member/matching-workouts-members/{memberId}', [MemberController::class, 'getMatchingWorkoutsMembers'])->name('club-members.get'); // Get all matching workout members
    
    Route::get('/trainers', [TrainerController::class, 'getAllTrainers'])->name('trainers.get'); // Get all trainers
    Route::get('/trainer/{id}', [TrainerController::class, 'getTrainer'])->name('trainer.get'); // Get a trainer
    
    Route::get('/admins', [AdminController::class, 'getAllAdmins'])->name('admins.get'); // Get all admins
    Route::get('/admin/{id}', [AdminController::class, 'getAdmin'])->name('admin.get'); // Get an admin
    
    Route::get('/buddy/list', [BuddiesController::class, 'getBuddies'])->name('buddies.get'); // Get all buddies
    Route::get('/buddy/list/{userId}', [BuddiesController::class, 'getBuddies'])->name('buddies.get'); // Get a user's buddies
    
    Route::get('/group-sessions/{trainerId}', [GroupSessionsController::class, 'getAllGroupSessions'])->name('group-sessions.get'); // Get all group sessions
    Route::get('/group-session/{sessionId}', [GroupSessionsController::class, 'getGroupSession'])->name('group-sessions.get'); // Get a user's group sessions
    });
    
    Route::get('/member/searching-members/{memberId}', [MemberController::class, 'searchingForMembers'])->name('club-members.get'); // Get all matching workout members
    
    Route::get('/member/members-you-might-know/{memberId}', [MemberController::class, 'getMembersYouMightKnow'])->name('club-members.get'); // Get all matching workout members


// // -------------- USER ROUTES : MEMBER, TRAINER, ADMIN -------------- 
// // ------- PUBLIC ROUTES ------- 
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::middleware(['customAuth'])->group(function () {
//     Route::post('/logout', [UserController::class, 'logout'])->name('user.logout'); // Logout user
//     Route::post('/register', [UserController::class, 'register'])->name('user.register'); // Register new user
//     Route::post('/', [TrainerController::class, 'createTrainer'])->name('trainer.create'); // Create trainer
// });

// // ------- PROTECTED ROUTES -------
// Route::group([
//     'middleware' => 'auth:api',
// ], function () {

//     // ----- USER ROUTES ----- 
//     // User Routes (for all users)
//     Route::prefix('user')->group(function () {
//         Route::get('/', [UserController::class, 'getUser'])->name('user.get'); // Get user
//         Route::put('/', [UserController::class, 'updateUser'])->name('user.update'); // Update user
//         Route::delete('/', [UserController::class, 'deleteUser'])->name('user.delete'); // Delete user
//     });
//     // User Routes (only for admins)
//     Route::middleware(['admin'])->prefix('user')->group(function () {
//         Route::get('user/{id}', [UserController::class, 'getUser'])->name('user.get'); // Get another user
//         Route::delete('/{id}', [UserController::class, 'deleteUser'])->name('user.delete'); // Delete user       
//     });

//     // ----- ADMIN ROUTES ----- 
//     // Admin Routes (only for admins)
//     Route::middleware(['admin'])->prefix('admin')->group(function () {
//         Route::get('/', [AdminController::class, 'getAdmin'])->name('admin.get'); // Get admin
//     });

//     // ----- MEMBER ROUTES -----
//     // Member Routes (only for members)
//     Route::middleware(['member'])->prefix('member')->group(function () {
//         Route::get('/', [MemberController::class, 'getMember'])->name('member.get'); // Get member
//         Route::put('/', [MemberController::class, 'updateMember'])->name('member.update'); // Update member
//     });
//     // Member Routes (only for admins)
//     Route::middleware(['admin'])->prefix('member')->group(function () {
//         Route::get('/{id}', [MemberController::class, 'getMember'])->name('member.get'); // Get member
//     });

//     // ----- TRAINER ROUTES -----
//     // Trainer Routes (only for trainers)
//     Route::middleware(['trainer'])->prefix('trainer')->group(function () {
//         Route::get('/', [TrainerController::class, 'getTrainer'])->name('trainer.get'); // Get trainer
//         Route::put('/', [TrainerController::class, 'updateTrainer'])->name('trainer.update'); // Update trainer
//     });
//     // Trainer Routes (only for admins)
//     Route::middleware(['admin'])->prefix('trainer')->group(function () {
//         Route::get('/{id}', [TrainerController::class, 'getTrainer'])->name('admin.trainer.get'); // Get trainer
//     });
// });
// Route::group([
//     'middleware' => 'auth:sanctum',
//     'middleware' => 'api-session',
// ], function () {

//     // User Routes
//     Route::prefix('user')->group(function () {
//         // Global Access
//         Route::post('/logout', [UserController::class, 'logout']);

//         // Admin Access
//         Route::middleware(['admin'])->group(function () {
//             Route::post('/register', [UserController::class, 'register']);
//         });
//     });

//     // Buddy Routes
//     Route::prefix('buddy')->group(function () {
//         Route::post('/add/{buddyId}', [BuddiesController::class, 'addBuddy'])->name('buddy.add');
//         Route::post('/accept/{buddyId}', [BuddiesController::class, 'acceptBuddy'])->name('buddy.accept');
//         Route::post('/reject/{buddyId}', [BuddiesController::class, 'rejectBuddy'])->name('buddy.reject');
//         Route::post('/remove/{buddyId}', [BuddiesController::class, 'removeBuddy'])->name('buddy.remove');
//         Route::get('/list', [BuddiesController::class, 'getBuddies'])->name('buddies.get');
//     });
// });
