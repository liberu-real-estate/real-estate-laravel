<?php

namespace App\Filament\Staff\Resources\TransactionResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\TransactionResource;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
