<?php

namespace App\Filament\Staff\Resources\Reviews\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Staff\Resources\Reviews\ReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReview extends EditRecord
{
    protected static string $resource = ReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
