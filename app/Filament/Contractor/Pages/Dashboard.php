<?php

namespace App\Filament\Contractor\Pages;

use Filament\Pages\Page;
use App\Models\Maintenance;
use App\Models\Job;

class Dashboard extends Page
{
    protected static string $view = 'filament.contractor.dashboard';

    public function mount(): void
    {
        $this->openJobs = Job::where('contractor_id', auth()->id())->where('status', 'open')->count();
        $this->completedJobs = Job::where('contractor_id', auth()->id())->where('status', 'completed')->count();
        $this->pendingPayments = Job::where('contractor_id', auth()->id())->where('payment_status', 'pending')->sum('amount');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Add any contractor-specific widgets here
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // Add any contractor-specific widgets here
        ];
    }
}