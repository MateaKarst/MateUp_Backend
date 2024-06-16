<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthenticatedSessionController;
use App\Http\Controllers\Web\RegisteredUserController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\TrainerController;
use App\Http\Controllers\Web\MemberController;


// PAGE ROUTES
// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Route to show login form
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');

// Route to handle login form submission
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// USER ROUTES




// DASHBOARD ROUTE
Route::group([
    'middleware' => 'api-session',
], function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::get('/users/{id}/delete', [UserController::class, 'confirmDelete'])->name('users.confirmDelete');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
