<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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
                            ->submit()
                            ->form('loginForm'),
                        Forms\Components\Button::make('Register')
                            ->submit()
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
                            ->submit()
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

    // protected static function handleLogin(array $data)
    // {
    //     if (!Auth::attempt($data)) {
    //         throw ValidationException::withMessages([
    //             'email' => [__('auth.failed')],
    //         ]);
    //     }
    // }

    // protected static function handleRegister(array $data)
    // {
    //     // Registration logic here
    // }

    // protected static function handleVerification(array $data)
    // {
    //     // Verification logic here
    // }

    // protected static function handleReset(array $data)
    // {
    //     $status = Password::sendResetLink($data);

    //     if ($status !== Password::RESET_LINK_SENT) {
    //         throw ValidationException::withMessages([
    //             'email' => [__($status)],
    //         ]);
    //     }
    // }
}
