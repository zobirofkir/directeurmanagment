<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\LogoutController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function() {
    Route::get('/admin/chats', [ChatController::class, 'index'])->name('admin.chats');

        Route::get('/messages/{user}', [ChatController::class, 'getMessages']);
        Route::post('/messages', [ChatController::class, 'sendMessage']);
        Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
});

