<?php

namespace App\Filament\Tenant\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\MaintenanceRequest;
use App\Models\WorkOrder;

class DashboardStatsOverview extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        $tenantId = auth()->id();

        return [
            Card::make('Total Maintenance Requests', MaintenanceRequest::where('tenant_id', $tenantId)->count()),
            Card::make('Pending Requests', MaintenanceRequest::where('tenant_id', $tenantId)->where('status', 'pending')->count()),
            Card::make('Completed Requests', MaintenanceRequest::where('tenant_id', $tenantId)->where('status', 'completed')->count()),
            Card::make('Open Work Orders', WorkOrder::whereHas('maintenanceRequest', function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId);
            })->whereIn('status', ['pending', 'in_progress'])->count())
                ->description('Current open work orders')
                ->descriptionIcon('heroicon-s-wrench')
                ->color('danger'),
            Card::make('Completed Work Orders', WorkOrder::whereHas('maintenanceRequest', function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId);
            })->where('status', 'completed')->count())
                ->description('Total completed work orders')
                ->descriptionIcon('heroicon-s-check-circle')
                ->color('success'),
        ];
    }
}