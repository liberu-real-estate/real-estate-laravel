<?php

namespace App\Filament\Staff\Resources\Reviews\Pages;

use App\Filament\Staff\Resources\Reviews\ReviewResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReview extends CreateRecord
{
    protected static string $resource = ReviewResource::class;
}
