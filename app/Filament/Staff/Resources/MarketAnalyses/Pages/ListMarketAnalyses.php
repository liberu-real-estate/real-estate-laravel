<?php

namespace App\Filament\Staff\Resources\MarketAnalyses\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Staff\Resources\MarketAnalyses\MarketAnalysisResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarketAnalyses extends ListRecords
{
    protected static string $resource = MarketAnalysisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}