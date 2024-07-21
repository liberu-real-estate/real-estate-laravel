<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Property;
use App\Models\Booking;

class RecentActivity extends Widget
{
    protected static string $view = 'filament.widgets.recent-activity';

    public function getRecentActivity()
    {
        $properties = Property::latest()->take(3)->get();
        $bookings = Booking::latest()->take(3)->get();

        return [
            'properties' => $properties,
            'bookings' => $bookings,
        ];
    }
}