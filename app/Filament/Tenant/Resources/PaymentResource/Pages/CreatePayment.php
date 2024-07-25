<?php

namespace App\Filament\Tenant\Resources\PaymentResource\Pages;

use App\Filament\Tenant\Resources\PaymentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;
}