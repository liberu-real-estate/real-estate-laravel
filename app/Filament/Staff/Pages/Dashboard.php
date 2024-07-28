<?php

namespace App\Filament\Staff\Pages;

use Filament\Pages\Page;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Transaction;
use App\Filament\Staff\Widgets\PropertyStatsOverview;
use App\Filament\Staff\Widgets\RecentTransactions;
use App\Filament\Staff\Widgets\TopPerformingProperties;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\DatePicker;

class Dashboard extends Page
{
    use InteractsWithForms;

    protected static string $view = 'filament.staff.dashboard';

    public $startDate;
    public $endDate;

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth();
        $this->endDate = now()->endOfMonth();

        $this->form->fill([
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);

        $this->loadDashboardData();
    }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('startDate')
                ->label('Start Date')
                ->default(now()->startOfMonth()),
            DatePicker::make('endDate')
                ->label('End Date')
                ->default(now()->endOfMonth()),
        ];
    }

    public function loadDashboardData(): void
    {
        $this->totalProperties = Property::count();
        $this->activeListings = Property::where('status', 'active')->count();
        $this->totalBookings = Booking::whereBetween('date', [$this->startDate, $this->endDate])->count();
        $this->totalRevenue = Transaction::whereBetween('transaction_date', [$this->startDate, $this->endDate])->sum('transaction_amount');
    }

    public function refreshDashboard(): void
    {
        $this->loadDashboardData();
    }

    protected function getHeaderWidgets(): array
    {
        return [
//            PropertyStatsOverview::class,
           // BookingStatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
//            RecentBookings::class,
//            TopPerformingProperties::class,
        ];
    }
}
