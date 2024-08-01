<?php

namespace App\Filament\App\Pages\Tenant;

use Filament\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;

class Profile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static string $view = 'filament.app.tenant.profile';
    protected static ?string $navigationLabel = 'Profile';
    protected static ?string $title = 'Profile';

    public ?array $data = [];

    public function mount(): void
    {
        $user = Auth::user();
        $userData = $user->attributesToArray();
        $userData['average_rating'] = $user->averageRating();
        $this->form->fill($userData);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('average_rating')
                    ->label('Average Rating')
                    ->disabled()
                    ->afterStateHydrated(function (TextInput $component, $state) {
                        $component->state(number_format($state, 2));
                    }),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        Auth::user()->update($data);

        $this->notify('success', 'Profile updated successfully.');
    }

    public static function getUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, $tenant = null): string
    {
        if ($tenant) {
            $parameters['tenant'] = $tenant;
        }
        return parent::getUrl($parameters, $isAbsolute, $panel);
    }

}
