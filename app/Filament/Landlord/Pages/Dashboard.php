<?php

namespace App\Filament\Landlord\Pages;

use Filament\Pages\Page;
use App\Models\Property;
use App\Models\Tenant;

class Dashboard extends Page
{
    protected static string $view = 'filament.landlord.dashboard';

    public function mount(): void
    {
        $this->totalProperties = Property::where('user_id', auth()->id())->count();
        $this->occupiedProperties = Property::where('user_id', auth()->id())->where('status', 'occupied')->count();
        $this->totalTenants = Tenant::whereHas('property', function ($query) {
            $query->where('user_id', auth()->id());
        })->count();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Add any landlord-specific widgets here
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // Add any landlord-specific widgets here
        ];
    }
}