<?php

namespace App\Filament\Tenant\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget;
use App\Models\MaintenanceRequest;
use App\Models\WorkOrder;

class TenantStatsOverview extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        $tenant = auth()->user();
        $activeLeases = $tenant->leases()->where('status', 'active')->count();
        $pendingPayments = $tenant->payments()->where('status', 'pending')->sum('amount');
        $openMaintenanceRequests = $tenant->maintenanceRequests()->whereIn('status', ['pending', 'in_progress'])->count();
        $openWorkOrders = WorkOrder::whereHas('maintenanceRequest', function ($query) {
            $query->where('tenant_id', auth()->id());
        })->whereIn('status', ['pending', 'in_progress'])->count();
        $completedWorkOrders = WorkOrder::whereHas('maintenanceRequest', function ($query) {
            $query->where('tenant_id', auth()->id());
        })->where('status', 'completed')->count();

        return [
            Stat::make('Active Leases', $activeLeases)
                ->description('Number of active leases')
                ->descriptionIcon('heroicon-s-home')
                ->color('primary'),
            Stat::make('Pending Payments', '$' . number_format($pendingPayments, 2))
                ->description('Total pending payments')
                ->descriptionIcon('heroicon-s-currency-dollar')
                ->color('warning'),
            Stat::make('Open Maintenance Requests', $openMaintenanceRequests)
                ->description('Number of open requests')
                ->descriptionIcon('heroicon-s-exclamation-circle')
                ->color('danger'),
            Stat::make('Open Work Orders', $openWorkOrders)
                ->description('Current open work orders')
                ->descriptionIcon('heroicon-s-wrench')
                ->color('danger'),
            Stat::make('Completed Work Orders', $completedWorkOrders)
                ->description('Total completed work orders')
                ->descriptionIcon('heroicon-s-check-circle')
                ->color('success'),
        ];
    }
}