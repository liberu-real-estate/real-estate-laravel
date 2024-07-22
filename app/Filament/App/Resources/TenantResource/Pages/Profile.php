<?php

namespace App\Filament\App\Resources\TenantResource\Pages;

use App\Filament\App\Resources\TenantResource;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;

class Profile extends Page
{
    protected static string $resource = TenantResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static string $view = 'filament.app.tenant.profile';
    protected static ?string $navigationLabel = 'Profile';
    protected static ?string $title = 'Profile';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Auth::user()->attributesToArray());
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