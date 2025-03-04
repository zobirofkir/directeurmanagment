<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function() {
    Route::get('/admin/chats', [ChatController::class, 'index'])->name('admin.chats');
});

