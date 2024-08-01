<?php

namespace App\Filament\Staff\Resources\MarketAnalysisResource\Pages;

use App\Filament\Staff\Resources\MarketAnalysisResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarketAnalyses extends ListRecords
{
    protected static string $resource = MarketAnalysisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}