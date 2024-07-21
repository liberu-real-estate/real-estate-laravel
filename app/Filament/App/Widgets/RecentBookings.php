<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Booking;

class RecentBookings extends Widget
{
    protected static string $view = 'filament.widgets.recent-bookings';

    public function getRecentBookings()
    {
        return Booking::latest()->take(5)->get();
    }
}