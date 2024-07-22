<?php

namespace App\Filament\Staff\Resources\TenantResource\Pages;

use Filament\Resources\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class Login extends Page
{
    protected static string $view = 'filament.resources.tenant-resource.pages.login';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('Username'),
                Forms\Components\PasswordInput::make('password')
                    ->required()
                    ->label('Password'),
            ]);
    }

    public function mount()
    {
        if (Auth::check()) {
            return Redirect::to('/dashboard');
        }
    }

    public function login(array $data)
    {
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            return Redirect::to($this->redirectTo);
        }

        $this->notify('danger', 'Invalid credentials.');
    }

    protected string $redirectTo = '/dashboard';
}
