<?php

use Filament\Forms\Get;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

// Route::post('/qr-login', function (Request $request) {
//     $inputToken = $request->input('token');
//     $currentToken = Cache::get('qr_code_token');

//     if ($inputToken === $currentToken) {
//         // Lakukan login (misalnya login user ID 1 atau berdasarkan token)
//         Auth::loginUsingId(1); // Login user ID 1
//         return redirect('/dashboard');
//     }

//     return response()->json(['error' => 'Invalid token'], 401);
// })->name('qr-login'); 

Route::get('/generate-qr', 'QrCodeController@generate')->name('generate-qr');



Route::get('/qrcode')->name('qrcode');
// Route::get('/qrcode', [QrCodeController::class, 'index'])->name('qrcode');

// this route work for trigger camera
Route::get('/start-camera', function () {
    return response()->json(['message' => 'Camera started successfully']);
})->name('start-camera');

use App\Filament\Pages\QRCodeLogin;

Route::get('/qr-code-login', [QRCodeLogin::class, 'render'])->name('qr-code-login');



