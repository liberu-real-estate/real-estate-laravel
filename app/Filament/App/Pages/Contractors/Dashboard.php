<?php

namespace App\Filament\App\Pages\Contractors;

use Illuminate\Contracts\View\View;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected string $view = 'filament.pages.contractors.dashboard';

    public function mount(): void
    {
        // Initialize any data or states required for the contractor dashboard
    }

    public function render(): View
    {
        return view(static::$view);
    }
}
