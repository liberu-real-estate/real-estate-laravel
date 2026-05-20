<?php

namespace App\Filament\Admin\Resources\Branches\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\Branches\BranchResource;
use Filament\Tables;

class ListBranches extends ListRecords
{
    protected static string $resource = BranchResource::class;

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')->label('Name')->sortable(),
            TextColumn::make('address')->label('Address')->sortable(),
            TextColumn::make('phone_number')->label('Phone Number')->sortable(),
        ];
    }
}
