<?php

use Illuminate\Support\Facades\Route;

// PAGE ROUTES
// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Protected Routes
Route::get('/home', function () {
    return view('home');
})->middleware('auth')->name('home');

Route::get('/user-management', function () {
    return view('user-management');
})->middleware('auth')->name('user-management');

// LIVEWIRE ROUTES
Route::view('login', 'livewire.auth.login');
Route::view('main-navigation', 'livewire.main-navigation');
Route::view('users', 'livewire.admin.users');
