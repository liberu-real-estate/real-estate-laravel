<?php

namespace App\Filament\Resources\BuyerResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\BuyerResource;
use Filament\Forms;

class EditBuyer extends EditRecord
{
    protected static string $resource = BuyerResource::class;

    protected function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Name'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('Email'),
                Forms\Components\TextInput::make('contact_number')
                    ->tel()
                    ->label('Contact Number'),
            ]);
    }
}
