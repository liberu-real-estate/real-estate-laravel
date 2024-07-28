<?php

namespace App\Filament\Staff\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Booking;
use Filament\Widgets\Widget;

class BookingStatsOverview extends BaseWidget
{
    use Widget;

    protected function getCards(): array
    {
        $startDate = $this->getPage()->startDate;
        $endDate = $this->getPage()->endDate;

        return [
            Card::make('Total Bookings', Booking::whereBetween('date', [$startDate, $endDate])->count())
                ->description('Bookings in selected period')
                ->descriptionIcon('heroicon-s-calendar')
                ->color('primary'),
            Card::make('Active Bookings', Booking::whereBetween('date', [$startDate, $endDate])->where('status', 'active')->count())
                ->description('Active bookings in selected period')
                ->descriptionIcon('heroicon-s-check-circle')
                ->color('success'),
            Card::make('Cancelled Bookings', Booking::whereBetween('date', [$startDate, $endDate])->where('status', 'cancelled')->count())
                ->description('Cancelled bookings in selected period')
                ->descriptionIcon('heroicon-s-x-circle')
                ->color('danger'),
            Card::make('Recent Bookings', Booking::whereBetween('date', [now()->subDays(7), now()])->count())
                ->description('Bookings in the last 7 days')
                ->descriptionIcon('heroicon-s-trending-up')
                ->color('warning'),
        ];
    }
}