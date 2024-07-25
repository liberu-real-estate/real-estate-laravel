<?php

namespace App\Filament\Tenant\Resources\TenantPortalResource\Pages;

use App\Filament\Tenant\Resources\TenantPortalResource;
use Filament\Resources\Pages\Page;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TenantDashboard extends Page
{
    protected static string $resource = TenantPortalResource::class;

    protected static string $view = 'filament.tenant.resources.tenant-portal-resource.pages.tenant-dashboard';

    public function getTitle(): string
    {
        return 'Tenant Dashboard';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TenantStatsOverview::class,
        ];
    }
}

class TenantStatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $tenant = auth()->user();
        $activeLeases = $tenant->leases()->where('status', 'active')->count();
        $pendingPayments = $tenant->payments()->where('status', 'pending')->sum('amount');
        $openMaintenanceRequests = $tenant->maintenanceRequests()->whereIn('status', ['pending', 'in_progress'])->count();

        return [
            Card::make('Active Leases', $activeLeases)
                ->description('Number of active leases')
                ->descriptionIcon('heroicon-s-home')
                ->color('primary'),
            Card::make('Pending Payments', '$' . number_format($pendingPayments, 2))
                ->description('Total pending payments')
                ->descriptionIcon('heroicon-s-currency-dollar')
                ->color('warning'),
            Card::make('Open Maintenance Requests', $openMaintenanceRequests)
                ->description('Number of open requests')
                ->descriptionIcon('heroicon-s-exclamation-circle')
                ->color('danger'),
        ];
    }
}