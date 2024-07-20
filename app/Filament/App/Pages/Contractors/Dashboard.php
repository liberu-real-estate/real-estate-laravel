<?php

namespace App\Filament\App\Pages\Contractors;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static string $view = 'filament.pages.contractors.dashboard';

    public function mount(): void
    {
        // Initialize any data or states required for the contractor dashboard
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view(static::$view);
    }
}
