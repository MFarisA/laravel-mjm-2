<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GenerateLoginLink extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-link';
    protected static ?string $title = 'Generate link';
    protected static string $view = 'filament.pages.generate-login-link';

    public $loginLink;

    public function generateLoginLink()
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Create a temporary signed URL valid for 10 minutes
        $this->loginLink = URL::temporarySignedRoute('login.via.link', now()->addMinutes(10), [
            'user' => $user->id,   // Pass the user's ID in the URL
            'token' => Str::random(40), // Generate a random token for security
        ]);
    }

    public function myAction()
    {
        // Your action logic
        $this->notify('success', 'Button was clicked!');
    }
}
