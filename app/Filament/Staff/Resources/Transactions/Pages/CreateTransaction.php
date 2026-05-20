<?php

namespace App\Filament\Staff\Resources\Transactions\Pages;

use App\Filament\Staff\Resources\Transactions\TransactionResource;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;
}
