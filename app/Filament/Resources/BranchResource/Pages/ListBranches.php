<?php

/**
 * Page for listing all Branch entities.
 * 
 * This file contains the class definition for the page used to list all Branch entities
 * within the Filament admin panel.
 */

namespace App\Filament\Resources\BranchResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\BranchResource;
use Filament\Tables;

class ListBranches extends ListRecords
{
    protected static $resource = BranchResource::class;

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')->label('Name')->sortable(),
            Tables\Columns\TextColumn::make('address')->label('Address')->sortable(),
            Tables\Columns\TextColumn::make('phone_number')->label('Phone Number')->sortable(),
        ];
    }
}
            Tables\Columns\TextColumn::make('address')->label('Address')->sortable(),
            Tables\Columns\TextColumn::make('phone_number')->label('Phone Number')->sortable(),
        ];
    }
}
    {
        return [
            Tables\Columns\TextColumn::make('name')->label('Name')->sortable(),
            Tables\Columns\TextColumn::make('address')->label('Address')->sortable(),
            Tables\Columns\TextColumn::make('phone_number')->label('Phone Number')->sortable(),
        ];
    }
}
