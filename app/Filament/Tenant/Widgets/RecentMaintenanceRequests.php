<?php

namespace App\Filament\Tenant\Widgets;

use App\Filament\Tenant\Resources\MaintenanceRequestResource;
use Filament\Tables;
use Filament\Widgets\TableWidget;
use App\Models\MaintenanceRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class RecentMaintenanceRequests extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder | Relation | null
    {
        return MaintenanceRequest::where('tenant_id', auth()->id())->latest()->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('title'),
            Tables\Columns\TextColumn::make('status'),
            Tables\Columns\TextColumn::make('requested_date')->date(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('view')
                ->url(fn (MaintenanceRequest $record): string => MaintenanceRequestResource::getUrl('edit', ['record' => $record])),
        ];
    }

    protected function getTableHeading(): string
    {
        return 'Recent Maintenance Requests';
    }
}