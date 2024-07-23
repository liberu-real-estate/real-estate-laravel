<?php

namespace App\Filament\Tenant\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\TableWidget;
use App\Models\Property;
use App\Models\MaintenanceRequest;
use App\Filament\Tenant\MaintenanceRequestResource;

class Dashboard extends BaseDashboard
{
    public function mount(): void
    {
        $this->currentProperty = Property::where('tenant_id', auth()->id())->first();
        $this->rentDueDate = $this->currentProperty ? $this->currentProperty->next_rent_due : null;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DashboardStatsOverview::class,
        ];
    }

    protected function getColumns(): int
    {
        return 2;
    }

    protected function getWidgets(): array
    {
        return [
            RecentMaintenanceRequests::class,
        ];
    }
}

class DashboardStatsOverview extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Maintenance Requests', MaintenanceRequest::where('tenant_id', auth()->id())->count()),
            Card::make('Pending Requests', MaintenanceRequest::where('tenant_id', auth()->id())->where('status', 'pending')->count()),
            Card::make('Completed Requests', MaintenanceRequest::where('tenant_id', auth()->id())->where('status', 'completed')->count()),
        ];
    }
}

class RecentMaintenanceRequests extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation|null
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
}