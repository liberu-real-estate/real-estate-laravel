<?php

/**
 * ListBuyers class is responsible for rendering the page used to list all `Buyer` entities within the Filament admin panel.
 */

namespace App\Filament\Resources\BuyerResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\BuyerResource;

class ListBuyers extends ListRecords
{
    protected static string $resource = BuyerResource::class;
}
