<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthenticatedSessionController;
use App\Http\Controllers\Web\RegisteredUserController;
use App\Http\Controllers\Web\UserController;


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
Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});