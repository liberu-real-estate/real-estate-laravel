<?php

namespace App\Filament\Admin\Resources\ModuleResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\ModuleResource;

class ListModules extends ListRecords
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('refresh')
                ->label('Refresh Modules')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    // Clear module cache and reload
                    cache()->forget('app.modules');
                    $this->redirect(request()->header('Referer'));
                }),
        ];
    }
}