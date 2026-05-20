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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home';
    protected string $view = 'filament.tenant.dashboard';

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

    public function getWidgets(): array
    {
        return [
            RecentMaintenanceRequests::class,
        ];
    }

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'sm' => 2,
            'lg' => 3,
        ];
    }
}
