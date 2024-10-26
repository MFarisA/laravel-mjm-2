<?php

use Filament\Forms\Get;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\BarcodeController;

use App\Models\User;
use Illuminate\Support\Facades\Auth;


Route::get('/qrcode')->name('qrcode');
// Route::get('/qrcode', [QrCodeController::class, 'index'])->name('qrcode');

// this route work for trigger camera
Route::get('/start-camera', function () {
    return response()->json(['message' => 'Camera started successfully']);
})->name('start-camera');

Route::get('/barcode', [BarcodeController::class, 'show'])->name('barcode');

Route::middleware(['signed'])->group(function () {
    Route::get('/login-via-link/{user}', function (Request $request, User $user) {
        if (!$request->hasValidSignature()) {
            return redirect('/login')->with('error', 'Invalid or expired login link.');
        }

        Auth::login($user);
        // Auth::loginUsingId($user->id);
        // dd(Auth::check());

        return redirect('/')->with('success', 'Logged in successfully!');
    })->name('login.via.link');
});


