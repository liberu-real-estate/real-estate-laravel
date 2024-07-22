<?php

<<<<<<< HEAD
namespace App\Filament\App\Resources\ReviewResource\Pages;

use App\Filament\App\Resources\ReviewResource;
=======
namespace App\Filament\Staff\Resources\ReviewResource\Pages;

use App\Filament\Staff\Resources\ReviewResource;
>>>>>>> refs/remotes/origin/main
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReview extends EditRecord
{
    protected static string $resource = ReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> refs/remotes/origin/main
