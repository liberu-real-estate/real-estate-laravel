<?php

namespace App\Filament\Staff\Resources\AgentPerformances\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use App\Filament\Staff\Resources\AgentPerformances\AgentPerformanceResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\TextEntry;

class ViewAgentPerformance extends ViewRecord
{
    protected static string $resource = AgentPerformanceResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $infolist
            ->schema([
                Section::make('Performance Metrics')
                    ->schema([
                        TextEntry::make('listings_count')
                            ->label('Total Listings'),
                        TextEntry::make('properties_sold_count')
                            ->label('Properties Sold'),
                        TextEntry::make('appointments_count')
                            ->label('Total Appointments'),
                        TextEntry::make('average_rating')
                            ->label('Average Rating'),
                    ]),
                Section::make('Recent Reviews')
                    ->schema([
                        TextEntry::make('reviews.comment')
                            ->label('Review')
                            ->listWithLineBreaks()
                            ->limitList(5),
                    ]),
            ]);
    }
}