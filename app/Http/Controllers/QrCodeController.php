<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    public function generate()
    {
        // Dapatkan token dari cache atau buat yang baru
        $secret = env('APP_SECRET_KEY', 'default_secret');
        $cacheKey = 'qr_code_token';
        
        if (!Cache::has($cacheKey)) {
            $token = hash_hmac('sha256', now()->timestamp, $secret);
            Cache::put($cacheKey, $token, now()->addMinute()); // Set token untuk 1 menit
        } else {
            $token = Cache::get($cacheKey);
        }

        // Buat QR Code
        $qrCode = QrCode::size(200)->generate($token);
        
        return response()->json(['qr_code' => $qrCode]);
    }
}
