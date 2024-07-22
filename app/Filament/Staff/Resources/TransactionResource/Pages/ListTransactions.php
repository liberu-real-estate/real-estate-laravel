<?php

<<<<<<< HEAD
namespace App\Filament\App\Resources\TransactionResource\Pages;

use App\Filament\App\Resources\TransactionResource;
=======
namespace App\Filament\Staff\Resources\TransactionResource\Pages;

use App\Filament\Staff\Resources\TransactionResource;
>>>>>>> refs/remotes/origin/main
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> refs/remotes/origin/main
