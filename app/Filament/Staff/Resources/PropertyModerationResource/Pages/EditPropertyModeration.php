<?php

namespace App\Filament\Staff\Resources\PropertyModerationResource\Pages;

use App\Filament\Staff\Resources\PropertyModerationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPropertyModeration extends EditRecord
{
    protected static string $resource = PropertyModerationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('approve')
                ->action(fn () => $this->record->approve())
                ->requiresConfirmation()
                ->color('success')
                ->icon('heroicon-o-check')
                ->visible(fn (): bool => $this->record->status === 'pending'),
            Actions\Action::make('reject')
                ->action(fn () => $this->record->reject())
                ->requiresConfirmation()
                ->color('danger')
                ->icon('heroicon-o-x')
                ->visible(fn (): bool => $this->record->status === 'pending'),
        ];
    }
}