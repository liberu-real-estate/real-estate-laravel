<?php

namespace App\Filament\Staff\Resources\MarketAnalyses\Pages;

use Filament\Actions\EditAction;
use App\Filament\Staff\Resources\MarketAnalyses\MarketAnalysisResource;
use App\Services\MarketAnalysisService;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

class ViewMarketAnalysis extends ViewRecord
{
    protected static string $resource = MarketAnalysisResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        $marketAnalysisService = app(MarketAnalysisService::class);

        $this->marketData = $marketAnalysisService->generateMarketAnalysis(
            $this->record->start_date,
            $this->record->end_date,
            $this->record->properties->pluck('id')->toArray()
        );

        $this->marketTrends = $marketAnalysisService->getMarketTrends(
            $this->record->start_date,
            $this->record->end_date,
            $this->record->properties->pluck('id')->toArray()
        );
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}