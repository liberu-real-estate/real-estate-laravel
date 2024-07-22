<?php

<<<<<<< HEAD
namespace App\Filament\App\Resources\ImageResource\Pages;

use App\Filament\App\Resources\ImageResource;
=======
namespace App\Filament\Staff\Resources\ImageResource\Pages;

use App\Filament\Staff\Resources\ImageResource;
>>>>>>> refs/remotes/origin/main
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImage extends EditRecord
{
    protected static string $resource = ImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
<<<<<<< HEAD
            Actions\DeleteAction::make(),
        ];
    }
}
=======
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
>>>>>>> refs/remotes/origin/main
