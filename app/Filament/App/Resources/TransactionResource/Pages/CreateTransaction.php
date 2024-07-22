<?php

namespace App\Filament\Staff\Resources\TransactionResource\Pages;

use App\Filament\Staff\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;
}
