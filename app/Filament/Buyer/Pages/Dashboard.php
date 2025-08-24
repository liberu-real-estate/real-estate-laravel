<?php

namespace App\Filament\Buyer\Pages;

use Filament\Pages\Page;
use App\Models\Property;
use App\Models\Booking;

class Dashboard extends Page
{
    protected string $view = 'filament.buyer.dashboard';

    protected $myBookings;
    protected $activeListings;
    protected $totalProperties;

    public function mount(): void
    {
        $this->totalProperties = Property::count();
        $this->activeListings = Property::where('status', 'active')->count();
        $this->myBookings = Booking::where('user_id', auth()->id())->count();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Add any buyer-specific widgets here
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // Add any buyer-specific widgets here
        ];
    }
}