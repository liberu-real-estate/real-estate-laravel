<?php

namespace App\Filament\App\Resources\BranchResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\App\Resources\BranchResource;
use Filament\Tables;

class ListBranches extends ListRecords
{
    protected static string $resource = BranchResource::class;

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')->label('Name')->sortable(),
            Tables\Columns\TextColumn::make('address')->label('Address')->sortable(),
            Tables\Columns\TextColumn::make('phone_number')->label('Phone Number')->sortable(),
        ];
    }
}
