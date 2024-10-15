<?php

namespace App\Filament\Auth;

use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Component;
use Filament\Actions\Action;
use Filament\Pages\Auth\Login as BaseAuth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache; // Import Cache
use Illuminate\Support\Facades\Http; 

class Login extends BaseAuth
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                        $this->getQrCodeFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }
    
    protected function getQrCodeFormComponent(): TextInput
    {
        return TextInput::make('qr_code')
            ->label('QR Code')
            ->disabled()
            ->extraInputAttributes(['id' => 'qr-code-output']); // Tambahkan ID untuk referensi di JS
    }

    public function getFormActions(): array
    {
        return [
            $this->getAuthenticateFormAction(),
            $this->barcodeAction(), // Tombol untuk QR Code
        ];
    }


    public function barcodeAction(): Action
    {
        return Action::make('QR Login')
            ->label('Scan QR Code')
            ->action(function () {
                // Menggunakan AJAX untuk mendapatkan QR Code
                $response = Http::get(route('generate-qr'));

                if ($response->successful()) {
                    $qrCode = $response->json('qr_code');

                    // Menggunakan JavaScript untuk menampilkan QR Code
                    return redirect()->route('login')->with(['qr_code' => $qrCode]);
                }

                throw ValidationException::withMessages([
                    'qr_code' => 'Unable to generate QR Code',
                ]);
            });
    }


    protected function getBarcodeFormComponent(): TextInput
    {
        return TextInput::make('barcode')
            ->label('QR Code')
            ->placeholder('Scan or enter QR Code')
            ->required()
            ->autocomplete('off');
    }
    
}
?>