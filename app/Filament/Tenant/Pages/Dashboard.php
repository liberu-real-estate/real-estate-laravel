<?php

namespace App\Filament\Tenant\Pages;

use Filament\Pages\Page;
use App\Models\Property;
use App\Models\Maintenance;

class Dashboard extends Page
{
    protected static string $view = 'filament.tenant.dashboard';

    public function mount(): void
    {
        $this->currentProperty = Property::where('tenant_id', auth()->id())->first();
        $this->rentDueDate = $this->currentProperty ? $this->currentProperty->next_rent_due : null;
        $this->openMaintenanceRequests = Maintenance::where('tenant_id', auth()->id())->where('status', 'open')->count();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Add any tenant-specific widgets here
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // Add any tenant-specific widgets here
        ];
    }
}