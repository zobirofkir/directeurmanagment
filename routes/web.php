<?php

use App\Http\Controllers\DocumentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/document/{document}/sign', [DocumentController::class, 'sign'])->name('document.sign');
Route::post('/document/{document}/download-signed', [DocumentController::class, 'downloadSignedDocument'])->name('document.download.signed');

require __DIR__.'/auth.php';
