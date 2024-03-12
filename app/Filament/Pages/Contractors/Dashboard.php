<?php

/**
 * Dashboard page for contractors within the Filament directory.
 * 
 * This page serves as the dashboard for contractors, providing necessary views and functionalities.
 */

namespace App\Filament\Pages\Contractors;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static string $view = 'filament.pages.contractors.dashboard';

    public function mount(): void
    {
        // Initialize any data or states required for the contractor dashboard
    }

    protected function render(): \Illuminate\Contracts\View\View
    {
        return view(static::$view);
    }
}
        return view(static::$view);
    }
}
    protected function render(): \Illuminate\Contracts\View\View
    {
        return view(static::$view);
    }
}
