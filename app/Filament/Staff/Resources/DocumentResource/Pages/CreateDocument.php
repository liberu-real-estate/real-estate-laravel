<?php

namespace App\Filament\Staff\Resources\DocumentResource\Pages;

use App\Filament\Staff\Resources\DocumentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;
}