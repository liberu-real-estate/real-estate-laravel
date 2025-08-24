<?php

namespace App\Filament\Staff\Resources\PropertyResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use App\Filament\Staff\Resources\PropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProperties extends ListRecords
{
    protected static string $resource = PropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('importCsv')
                ->label('Import CSV')
                ->action(fn () => $this->importCsv()),
            Action::make('syncPortals')
                ->label('Sync Portals')
                ->action(fn () => $this->syncPortals()),
        ];
    }

    protected function importCsv()
    {
        // Implement CSV import logic here
    }

    protected function syncPortals()
    {
        // Implement property portal syncing logic here
    }
}
