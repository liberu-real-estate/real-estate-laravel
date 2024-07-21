<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use App\Http\Livewire\AdvancedPropertySearch;
use App\Http\Livewire\PropertyBooking;
use App\Http\Livewire\PropertyDetail;
use App\Http\Livewire\PropertyList;
use App\Http\Livewire\PropertyComparison;
use App\Http\Livewire\BookingCalendar;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Livewire::component('advanced-property-search', AdvancedPropertySearch::class);
        Livewire::component('property-booking', PropertyBooking::class);
        Livewire::component('property-detail', PropertyDetail::class);
        Livewire::component('property-list', PropertyList::class);
        Livewire::component('property-comparison', PropertyComparison::class);
        Livewire::component('booking-calendar', BookingCalendar::class);
    }
}
