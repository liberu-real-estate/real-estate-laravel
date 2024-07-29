<?php

namespace App\Filament\Tenant\Widgets;

use Filament\Widgets\TableWidget;
use App\Models\MaintenanceRequest;
use App\Filament\Tenant\Resources\MaintenanceRequestResource;

class RecentMaintenanceRequests extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery()
    {
        return MaintenanceRequest::where('tenant_id', auth()->id())->latest()->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            TableWidget\Columns\TextColumn::make('title'),
            TableWidget\Columns\TextColumn::make('status'),
            TableWidget\Columns\TextColumn::make('requested_date')->date(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            TableWidget\Actions\Action::make('view')
                ->url(fn (MaintenanceRequest $record): string => MaintenanceRequestResource::getUrl('edit', ['record' => $record])),
        ];
    }

    protected function getTableHeading(): string
    {
        return 'Recent Maintenance Requests';
    }
}