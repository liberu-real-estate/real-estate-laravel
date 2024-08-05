<?php

namespace App\Filament\Contractor\Pages;

use Filament\Pages\Page;
use App\Models\Job;
use App\Models\Maintenance;
use App\Models\WorkOrder;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as FilamentStatsOverviewWidget;

class Dashboard extends Page
{
    protected static string $view = 'filament.contractor.dashboard';

    public $openJobs;
    public $completedJobs;
    public $pendingPayments;
    public $upcomingJobs;
    public $openWorkOrders;
    public $completedWorkOrders;

    public function mount(): void
    {
        // $this->openJobs = Job::where('contractor_id', auth()->id())->where('status', 'open')->count();
        // $this->completedJobs = Job::where('contractor_id', auth()->id())->where('status', 'completed')->count();
        // $this->pendingPayments = Job::where('contractor_id', auth()->id())->where('payment_status', 'pending')->sum('amount');
        // $this->upcomingJobs = Job::where('contractor_id', auth()->id())->where('start_date', '>', now())->count();
        // $this->openWorkOrders = WorkOrder::where('contractor_id', auth()->id())->whereIn('status', ['pending', 'in_progress'])->count();
        // $this->completedWorkOrders = WorkOrder::where('contractor_id', auth()->id())->where('status', 'completed')->count();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // StatsOverviewWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // Widgets\LatestJobsWidget::class,
            // Widgets\UpcomingJobsWidget::class,
            // Widgets\OpenWorkOrdersWidget::class,
        ];
    }

    protected function getViewData(): array
    {
        return [
            'openJobs' => $this->openJobs,
            'completedJobs' => $this->completedJobs,
            'pendingPayments' => $this->pendingPayments,
            'upcomingJobs' => $this->upcomingJobs,
            'openWorkOrders' => $this->openWorkOrders,
            'completedWorkOrders' => $this->completedWorkOrders,
        ];
    }
}

class StatsOverviewWidget extends FilamentStatsOverviewWidget
{
    protected function getCards(): array
    {
        // return [
        //     Card::make('Open Jobs', $this->openJobs)
        //         ->description('Current open jobs')
        //         ->descriptionIcon('heroicon-s-clipboard-list')
        //         ->color('primary'),
        //     Card::make('Completed Jobs', $this->completedJobs)
        //         ->description('Total completed jobs')
        //         ->descriptionIcon('heroicon-s-check-circle')
        //         ->color('success'),
        //     Card::make('Pending Payments', '$' . number_format($this->pendingPayments, 2))
        //         ->description('Total pending payments')
        //         ->descriptionIcon('heroicon-s-currency-dollar')
        //         ->color('warning'),
        //     Card::make('Upcoming Jobs', $this->upcomingJobs)
        //         ->description('Jobs starting soon')
        //         ->descriptionIcon('heroicon-s-calendar')
        //         ->color('info'),
        //     Card::make('Open Work Orders', $this->openWorkOrders)
        //         ->description('Current open work orders')
        //         ->descriptionIcon('heroicon-s-wrench')
        //         ->color('danger'),
        //     Card::make('Completed Work Orders', $this->completedWorkOrders)
        //         ->description('Total completed work orders')
        //         ->descriptionIcon('heroicon-s-check-circle')
        //         ->color('success'),
        // ];
        return [];
    }
}
