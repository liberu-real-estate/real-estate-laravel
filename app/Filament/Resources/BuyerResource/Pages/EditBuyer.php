<?php

/**
 * EditBuyer class is responsible for rendering the page used to edit existing `Buyer` entities within the Filament admin panel.
 */

namespace App\Filament\Resources\BuyerResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\BuyerResource;

class EditBuyer extends EditRecord
{
    protected static string $resource = BuyerResource::class;
}
