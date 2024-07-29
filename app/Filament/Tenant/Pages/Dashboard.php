<?php

namespace App\Filament\Tenant\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Models\Property;
use App\Models\WorkOrder;
use App\Filament\Tenant\Widgets\DashboardStatsOverview;
use App\Filament\Tenant\Widgets\RecentMaintenanceRequests;

class Dashboard extends BaseDashboard
{
    public $currentProperty;
    public $rentDueDate;
    public $openWorkOrders;
    public $completedWorkOrders;

    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.tenant.dashboard';

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

    protected function getHeaderWidgets(): array
    {
        return [
            DashboardStatsOverview::class,
        ];
    }

    protected function getWidgets(): array
    {
        return [
            RecentMaintenanceRequests::class,
        ];
    }
}
