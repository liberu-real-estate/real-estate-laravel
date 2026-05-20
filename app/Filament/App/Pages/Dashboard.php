<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Transaction;

class Dashboard extends Page
{
    protected string $view = 'filament.pages.dashboard';

    public function mount(): void
    {
        $this->totalProperties = Property::count();
        $this->activeListings = Property::where('status', 'active')->count();
        $this->totalBookings = Booking::count();
        $this->totalRevenue = Transaction::sum('transaction_amount');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Add any app-specific widgets here
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // Add any app-specific widgets here
        ];
    }
}
