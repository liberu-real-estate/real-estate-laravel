<?php

namespace App\Filament\Contractor\Pages;

use Filament\Pages\Page;
use App\Models\Job;
use App\Models\Maintenance;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as FilamentStatsOverviewWidget;

class Dashboard extends Page
{
    protected static string $view = 'filament.contractor.dashboard';

    public $openJobs;
    public $completedJobs;
    public $pendingPayments;
    public $upcomingJobs;

    public function mount(): void
    {
        $this->openJobs = Job::where('contractor_id', auth()->id())->where('status', 'open')->count();
        $this->completedJobs = Job::where('contractor_id', auth()->id())->where('status', 'completed')->count();
        $this->pendingPayments = Job::where('contractor_id', auth()->id())->where('payment_status', 'pending')->sum('amount');
        $this->upcomingJobs = Job::where('contractor_id', auth()->id())->where('start_date', '>', now())->count();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            Widgets\LatestJobsWidget::class,
            Widgets\UpcomingJobsWidget::class,
        ];
    }

    protected function getViewData(): array
    {
        return [
            'openJobs' => $this->openJobs,
            'completedJobs' => $this->completedJobs,
            'pendingPayments' => $this->pendingPayments,
            'upcomingJobs' => $this->upcomingJobs,
        ];
    }
}

class StatsOverviewWidget extends FilamentStatsOverviewWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Open Jobs', $this->openJobs)
                ->description('Current open jobs')
                ->descriptionIcon('heroicon-s-clipboard-list')
                ->color('primary'),
            Card::make('Completed Jobs', $this->completedJobs)
                ->description('Total completed jobs')
                ->descriptionIcon('heroicon-s-check-circle')
                ->color('success'),
            Card::make('Pending Payments', '$' . number_format($this->pendingPayments, 2))
                ->description('Total pending payments')
                ->descriptionIcon('heroicon-s-currency-dollar')
                ->color('warning'),
            Card::make('Upcoming Jobs', $this->upcomingJobs)
                ->description('Jobs starting soon')
                ->descriptionIcon('heroicon-s-calendar')
                ->color('info'),
        ];
    }
}
