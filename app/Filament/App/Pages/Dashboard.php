<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\LatestProperties;
use App\Filament\Widgets\RecentBookings;
use App\Filament\Widgets\RecentActivity;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Transaction;

class Dashboard extends Page
{
    protected static string $view = 'filament.pages.dashboard';

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
            StatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            LatestProperties::class,
            RecentBookings::class,
            RecentActivity::class,
        ];
    }
}
