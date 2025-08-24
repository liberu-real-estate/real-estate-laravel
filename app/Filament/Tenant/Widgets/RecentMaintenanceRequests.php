<?php

namespace App\Filament\Tenant\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
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
            TextColumn::make('title'),
            TextColumn::make('status'),
            TextColumn::make('requested_date')->date(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('view')
                ->url(fn (MaintenanceRequest $record): string => MaintenanceRequestResource::getUrl('edit', ['record' => $record])),
        ];
    }

    protected function getTableHeading(): string
    {
        return 'Recent Maintenance Requests';
    }
}