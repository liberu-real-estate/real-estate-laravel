<?php

namespace App\Filament\Staff\Resources\News\Pages;

use App\Filament\Staff\Resources\News\NewsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNews extends CreateRecord
{
    protected static string $resource = NewsResource::class;
}
