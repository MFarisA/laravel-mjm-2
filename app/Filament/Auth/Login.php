<?php

namespace App\Filament\Auth;

use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Component;
use Filament\Actions\Action;
use Filament\Pages\Auth\Login as BaseAuth;
use Illuminate\Validation\ValidationException;

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
                    ])
                    ->statePath('data'),
            ),
        ];
    }
    

    public function barcodeAction(): Action
    {
        return Action::make('barcode')
        ->url(fn (): string => route('barcode'));
    }


    protected function getFormActions(): array
    {
        return [
            $this->getAuthenticateFormAction(),
            $this->barcodeAction(),
        ];
    }

    protected function getBarcodeFormComponent(): Component
    {
        return TextInput::make('barcode')
            ->label(__('filament-panels::pages/auth/login.form.email.label'))
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }
    
}
?>