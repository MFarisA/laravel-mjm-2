<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Http\Controllers\GenerateQrCodeToken; // Pastikan untuk mengimpor controller yang benar

class QRCodeLogin extends Page
{
    protected static ?string $navigationLabel = 'QR Code Login';

    // Metode ini digunakan untuk merender halaman Filament
    protected function getViewData(): array
    {
        // Panggil controller untuk mendapatkan QR Code
        $qrCodeController = new GenerateQrCodeToken();
        $qrCode = $qrCodeController->generateQrCode();

        return [
            'qrCode' => $qrCode,
        ];
    }

    // Pastikan view yang digunakan kompatibel dengan Filament
    protected static string $view = 'filament.pages.qr-code-login';
}


