<?php

namespace App\Filament\Admin\Pages\Tenant;

use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;

class Profile extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user';
    protected string $view = 'filament.app.tenant.profile';
    protected static ?string $navigationLabel = 'Profile';
    protected static ?string $title = 'Profile';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Auth::user()->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        Auth::user()->update($data);

        $this->notify('success', 'Profile updated successfully.');
    }
}
