<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use App\Filament\Resources\Handlers\TenantLoginHandler;
use App\Filament\Resources\Handlers\TenantRegisterHandler;
use App\Filament\Resources\Handlers\TenantVerificationHandler;
use App\Filament\Resources\Handlers\TenantResetHandler;
use Illuminate\Validation\ValidationException;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),
                        Forms\Components\PasswordInput::make('password')
                            ->required(),
                        Forms\Components\Button::make('Login')
                            ->action([TenantLoginHandler::class, 'handle'])
                            ->form('loginForm'),
                        Forms\Components\Button::make('Register')
                            ->action([TenantRegisterHandler::class, 'handle'])
                            ->form('registerForm'),
                    ]),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('token')
                            ->required(),
                        Forms\Components\Button::make('Verify Email')
                            ->action([TenantVerificationHandler::class, 'handle'])
                            ->form('verificationForm'),
                    ]),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),
                        Forms\Components\Button::make('Reset Password')
                            ->submit()
                            ->form('resetForm'),
                    ]),
            ]);
    }

                        Forms\Components\Button::make('Reset Password')
                            ->action([TenantResetHandler::class, 'handle'])
                            ->form('resetForm'),
}
