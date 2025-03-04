<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\DocumentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::middleware('auth')->group(function() {
    /**
     * Get Documents
     */
    Route::get('/document/{document}/sign', [DocumentController::class, 'sign'])->name('document.sign');

    /**
     * Download Signed Document
     */
    Route::post('/document/{document}/download-signed', [DocumentController::class, 'downloadSignedDocument'])->name('document.download.signed');
});

require __DIR__.'/auth.php';
require __DIR__.'/inertia.php';
