<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateQrCodeToken extends Controller
{
    public function generateQrCodeToken()
    {
        // Dapatkan secret dari .env
        $secret = env('APP_SECRET_KEY', 'default_secret');

        // Refresh token setiap 1 menit
        $cacheKey = 'qr_code_token';
        if (!Cache::has($cacheKey)) {
            $token = hash_hmac('sha256', now()->timestamp, $secret);
            Cache::put($cacheKey, $token, now()->addMinute()); // Set token untuk 1 menit
        } else {
            $token = Cache::get($cacheKey);
        }

        return $token;
    }

    public function generateQrCode()
    {
        // Panggil generateQrCodeToken menggunakan $this->
        $token = $this->generateQrCodeToken();
        return QrCode::size(200)->generate($token);
    }
}
