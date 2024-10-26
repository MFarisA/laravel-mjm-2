<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;

class GenerateLoginLink extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-link';
    protected static ?string $title = 'Generate link';
    protected static string $view = 'filament.pages.generate-login-link';

    public ?string $loginLink = null;  
    public ?string $qrCode = null;      

    public function generateLoginLink()
    {
        $user = Auth::user();

        $this->loginLink = URL::temporarySignedRoute('login.via.link', now()->addMinutes(10), [
            'user' => $user->id,
            'token' => Str::random(40),
        ]);
    }

    public function convertLinkToQrCode()
    {
        if ($this->loginLink) {
            $this->qrCode = QrCode::size(200)->generate($this->loginLink);
        }
    }

    public function myAction()
    {
        $this->notify('success', 'Button was clicked!');
    }
}
