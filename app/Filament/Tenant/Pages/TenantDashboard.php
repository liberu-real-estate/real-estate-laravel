<?php

namespace App\Filament\Tenant\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Models\Property;
use App\Models\MaintenanceRequest;
use App\Models\WorkOrder;
use App\Filament\Tenant\Resources\TenantPortalResource;

class TenantDashboard extends BaseDashboard
{
    protected static string $view = 'filament.tenant.pages.dashboard';

    public $currentProperty;
    public $rentDueDate;
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

    public function getTitle(): string
    {
        return 'Tenant Dashboard';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Tenant\Widgets\TenantStatsOverview::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Tenant\Widgets\RecentMaintenanceRequests::class,
        ];
    }

    public function getColumns(): int
    {
        return 2;
    }
}
