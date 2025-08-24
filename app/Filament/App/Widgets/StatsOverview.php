<?php

namespace App\Filament\App\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Transaction;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Stat::make('Total Properties', Property::count()),
            Stat::make('Active Listings', Property::where('status', 'active')->count()),
            Stat::make('Total Bookings', Booking::count()),
            Stat::make('Total Revenue', Transaction::sum('transaction_amount')),
        ];
    }
}