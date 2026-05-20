<?php

namespace App\Filament\Staff\Resources\Transactions\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\Transactions\TransactionResource;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
