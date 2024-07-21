<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Transaction;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Properties', Property::count()),
            Card::make('Active Listings', Property::where('status', 'active')->count()),
            Card::make('Total Bookings', Booking::count()),
            Card::make('Total Revenue', Transaction::sum('transaction_amount')),
        ];
    }
}