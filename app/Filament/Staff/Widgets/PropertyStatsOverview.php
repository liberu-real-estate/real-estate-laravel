<?php

namespace App\Filament\Staff\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Transaction;
use Filament\Widgets\Widget;
use Carbon\Carbon;

class PropertyStatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        return [
            Card::make('Total Properties', Property::count())
                ->description('Overall property count')
                ->descriptionIcon('heroicon-s-home')
                ->color('primary'),
            Card::make('Active Listings', Property::where('status', 'active')->count())
                ->description('Currently active properties')
                ->descriptionIcon('heroicon-s-clipboard-check')
                ->color('success'),
            Card::make('Total Bookings', Booking::whereBetween('date', [$startDate, $endDate])->count())
                ->description('Bookings this month')
                ->descriptionIcon('heroicon-s-calendar')
                ->color('warning'),
            Card::make('Total Revenue', Transaction::whereBetween('transaction_date', [$startDate, $endDate])->sum('transaction_amount'))
                ->description('Revenue this month')
                ->descriptionIcon('heroicon-s-currency-dollar')
                ->color('success'),
        ];
    }
}
