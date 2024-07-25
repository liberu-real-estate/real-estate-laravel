<?php

namespace App\Filament\Tenant\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\TableWidget;
use App\Models\Property;
use App\Models\MaintenanceRequest;
use App\Models\WorkOrder;
use App\Filament\Tenant\MaintenanceRequestResource;

class Dashboard extends BaseDashboard
{
    public $openWorkOrders;
    public $completedWorkOrders;

    public function mount(): void
    {
        $this->currentProperty = Property::where('tenant_id', auth()->id())->first();
        $this->rentDueDate = $this->currentProperty ? $this->currentProperty->next_rent_due : null;
        $this->openWorkOrders = WorkOrder::whereHas('maintenanceRequest', function ($query) {
            $query->where('tenant_id', auth()->id());
        })->whereIn('status', ['pending', 'in_progress'])->count();
        $this->completedWorkOrders = WorkOrder::whereHas('maintenanceRequest', function ($query) {
            $query->where('tenant_id', auth()->id());
        })->where('status', 'completed')->count();
    }

    public function getHeaderWidgets(): array
    {
        return [
            DashboardStatsOverview::class,
        ];
    }

    public function getColumns(): int
    {
        return 2;
    }

    public function getWidgets(): array
    {
        return [
            RecentMaintenanceRequests::class,
        ];
    }
}

class DashboardStatsOverview extends StatsOverviewWidget
{
    public function getCards(): array
    {
        return [
            Card::make('Total Maintenance Requests', MaintenanceRequest::where('tenant_id', auth()->id())->count()),
            Card::make('Pending Requests', MaintenanceRequest::where('tenant_id', auth()->id())->where('status', 'pending')->count()),
            Card::make('Completed Requests', MaintenanceRequest::where('tenant_id', auth()->id())->where('status', 'completed')->count()),
            Card::make('Open Work Orders', $this->openWorkOrders)
                ->description('Current open work orders')
                ->descriptionIcon('heroicon-s-wrench')
                ->color('danger'),
            Card::make('Completed Work Orders', $this->completedWorkOrders)
                ->description('Total completed work orders')
                ->descriptionIcon('heroicon-s-check-circle')
                ->color('success'),
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
