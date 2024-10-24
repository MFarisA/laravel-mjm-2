<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BarcodeController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        // Generate a random token
        $token = Str::random(10); // You can adjust the length as needed
        $qrCode = QrCode::size(200)->generate($token); // Generate QR code with the token

        return view('barcode.show', compact('qrCode', 'token'));
    }



}
