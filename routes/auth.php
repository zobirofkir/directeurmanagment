<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'index'])->name('index');

/**
 * Login Route
 */
Route::post('/login', [LoginController::class, 'login'])->name('login');

/**
 * Logout Route
 */
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
