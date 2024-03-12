<?php

namespace App\Filament\Resources\BuyerResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\BuyerResource;

/**
 * CreateBuyer class is responsible for rendering the page used to create new `Buyer` entities within the Filament admin panel.
 */
class CreateBuyer extends CreateRecord
{
    protected static string $resource = BuyerResource::class;
}
