<?php

use Filament\Forms\Get;
use Illuminate\Support\Facades\Route;


Route::get('/barcode')->name('barcode');

// this route work for trigger camera
Route::get('/start-camera', function () {
    return response()->json(['message' => 'Camera started successfully']);
})->name('start-camera');