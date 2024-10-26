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
        $token = Str::random(10); 
        $qrCode = QrCode::size(200)->generate($token); 

        // Pass both qrCode and token to the view
        return view('barcode.show', compact('qrCode', 'token'));
    }
}
