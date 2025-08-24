<?php

namespace App\Filament\Staff\Resources\Images\Pages;

use App\Filament\Staff\Resources\Images\ImageResource;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateImage extends CreateRecord
{
    protected static string $resource = ImageResource::class;
}
