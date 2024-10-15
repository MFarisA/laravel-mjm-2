<?php

use Filament\Forms\Get;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/qrcode')->name('qrcode');
// Route::get('/qrcode', [QrCodeController::class, 'index'])->name('qrcode');

// this route work for trigger camera
Route::get('/start-camera', function () {
    return response()->json(['message' => 'Camera started successfully']);
})->name('start-camera');


