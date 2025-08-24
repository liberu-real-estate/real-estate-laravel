<?php

namespace App\Filament\Staff\Resources\AgentPerformances\Pages;

use Filament\Actions\Action;
use App\Filament\Staff\Resources\AgentPerformances\AgentPerformanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgentPerformance extends ListRecords
{
    protected static string $resource = AgentPerformanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateReport')
                ->label('Generate Report')
                ->action(function () {
                    // TODO: Implement report generation logic
                    // This could involve creating a PDF or Excel file with detailed performance metrics
                })
                ->color('success'),
        ];
    }
}