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
use App\Http\Controllers\Api\ChallengesController;
use App\Models\Challenges;
use GuzzleHttp\Middleware;

// ------- OPEN ROUTES ------- 
Route::group([
    'middleware' => 'api-session',
], function () {
    Route::prefix('user')->group(function () {
        Route::post('/login', [UserController::class, 'login']);
    });
});

// ------- PROTECTED ROUTES - Postman ------- 
Route::group([
    'middleware' => [
        'auth:sanctum',
        'api-session',
        'check-token'
    ],
], function () {

    // ----- USER ROUTES ----- 
    // User Routes (for all users)
    Route::prefix('user')->group(function () {
        Route::post('/logout', [UserController::class, 'logout'])->name('user.logout'); // Logout user
        Route::get('/', [UserController::class, 'getUser'])->name('user.get'); // Get user
        Route::get('/{userId}', [UserController::class, 'getUser'])->name('user.getOther'); // Get another user
        Route::put('/', [UserController::class, 'updateUser'])->name('user.update'); // Update user
    });

    // User Routes (only for admins)
    Route::middleware(['check-token', 'admin'])->prefix('user')->group(function () {
        Route::delete('/{userId}', [UserController::class, 'deleteUser'])->name('user.deleteOther'); // Delete user 
        Route::put('/{userId}', [UserController::class, 'updateUser'])->name('user.updateOther'); // Update another user
        Route::post('/register', [UserController::class, 'register'])->name('user.register'); // Register new user
    });

    // ----- ADMIN ROUTES ----- 
    // Admin Routes (only for admins)
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/{adminId}', [AdminController::class, 'getAdmin'])->name('admin.getOther'); // Get admin
    });

    // ----- MEMBER ROUTES -----
    // Member Routes
    Route::prefix('member')->group(function () {
        Route::get('/{memberId}', [MemberController::class, 'getMember'])->name('member.getOther'); // Get member
    });

    // Member Routes (only for members)
    Route::middleware(['member'])->prefix('member')->group(function () {
        Route::get('/', [MemberController::class, 'getMember'])->name('member.get'); // Get member
        Route::put('/', [MemberController::class, 'updateMember'])->name('member.update'); // Update member
    });

    // Member Routes (only for admins)
    Route::middleware(['admin'])->prefix('member')->group(function () {
        Route::put('/{memberId}', [MemberController::class, 'getMember'])->name('member.updateOther'); // Update member
    });

    // ----- TRAINER ROUTES -----
    // Trainer Routes
    Route::prefix('trainer')->group(function () {
        Route::get('/{trainerId}', [TrainerController::class, 'getTrainer'])->name('trainer.getOther'); // Get trainer
    });

    // Trainer Routes (only for trainers)
    Route::middleware(['trainer'])->prefix('trainer')->group(function () {
        Route::get('/', [TrainerController::class, 'getTrainer'])->name('trainer.get'); // Get trainer
        Route::put('/', [TrainerController::class, 'updateTrainer'])->name('trainer.update'); // Update trainer
    });

    // Trainer Routes (only for admins)
    Route::middleware(['admin'])->prefix('trainer')->group(function () {
        Route::put('/{userId}', [TrainerController::class, 'updateTrainer'])->name('trainer.updateOther'); // Update trainer
        Route::post('/{userId}', [TrainerController::class, 'createTrainer'])->name('trainer.create'); // Create trainer
    });

    // ----- BUDDY ROUTES -----

    // Buddy Routes 
    Route::prefix('buddy')->group(function () {
        Route::get('/list/{userId}', [BuddiesController::class, 'getBuddies'])->name('buddies.getOther');
    });

    // Buddy Routes (only for members)
    Route::middleware(['member'])->prefix('buddy')->group(function () {
        Route::post('/add/{buddyId}', [BuddiesController::class, 'addBuddy'])->name('buddy.add');
        Route::post('/accept/{buddyId}', [BuddiesController::class, 'acceptBuddy'])->name('buddy.accept');
        Route::post('/reject/{buddyId}', [BuddiesController::class, 'rejectBuddy'])->name('buddy.reject');
        Route::post('/remove/{buddyId}', [BuddiesController::class, 'removeBuddy'])->name('buddy.remove');
        Route::get('/list', [BuddiesController::class, 'getBuddies'])->name('buddies.get');
    });
});

// ------- APPLICATION ROUTES - Frontend ------- 
Route::group([
    'middleware' => [
        'auth:sanctum',
        'api-session',
        'check-token'
    ],
], function () {
    // User Routes
    Route::get('/users', [UserController::class, 'getAllUsers'])->name('users.get'); // Get all users

    // Member Routes
    Route::get('/members', [MemberController::class, 'getAllMembers'])->name('members.get'); // Get all members
    Route::prefix('member')->group(function () {
        Route::get('/club-members/{memberId}', [MemberController::class, 'getMemberClubMembers'])->name('club-members.get'); // Get all club members of a member
        Route::get('/matching-workouts-members/{memberId}', [MemberController::class, 'getMatchingWorkoutsMembers'])->name('club-members.get'); // Get all matching workout members
        Route::get('/searching-members/{memberId}', [MemberController::class, 'searchingForMembers'])->name('club-members.get'); // Get all matching workout members
        Route::get('/members-you-might-know/{memberId}', [MemberController::class, 'getMembersYouMightKnow'])->name('club-members.get'); // Get all matching workout members
        Route::get('/connect-other-members/{memberId}', [MemberController::class, 'getConnectOtherMembers'])->name('club-members.get'); // Get all matching workout members
    });

    // Trainer Routes
    Route::get('/trainers', [TrainerController::class, 'getAllTrainers'])->name('trainers.get'); // Get all trainers

    // Group Session Routes
    Route::get('/group-sessions/{trainerId}', [GroupSessionsController::class, 'getAllGroupSessions'])->name('group-sessions.get'); // Get all group sessions
    Route::get('/group-session/{sessionId}', [GroupSessionsController::class, 'getGroupSession'])->name('group-sessions.get'); // Get a user's group sessions

    // Challenge Routes
    Route::get('/challenges/club-challenges/{memberId}', [ChallengesController::class, 'getMemberClubChallenges'])->name('challenges.get'); // Get all challenges
});
