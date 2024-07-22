<?php

namespace App\Filament\Seller\Pages;

use Filament\Pages\Page;
use App\Models\Property;
use App\Models\Booking;

class Dashboard extends Page
{
    protected static string $view = 'filament.seller.dashboard';

    public function mount(): void
    {
        $this->myProperties = Property::where('seller_id', auth()->id())->count();
        $this->activeListings = Property::where('seller_id', auth()->id())->where('status', 'active')->count();
        $this->totalBookings = Booking::whereHas('property', function ($query) {
            $query->where('seller_id', auth()->id());
        })->count();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Add any seller-specific widgets here
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // Add any seller-specific widgets here
        ];
    }
}