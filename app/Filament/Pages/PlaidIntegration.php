<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\View\View;

class PlaidIntegration extends Page
{
    protected static string $view = 'filament.pages.plaid-integration';

    public function mount(): void
    {
        // Initialize Plaid API configuration and any necessary data for integration
        // Assuming Plaid configuration is stored in .env
        $this->plaidClientId = config('services.plaid.client_id');
        $this->plaidSecret = config('services.plaid.secret');
        $this->plaidEnvironment = config('services.plaid.env');
    }

    protected function render(): View
    {
        return view(static::$view, [
            // Data to be passed to the view, if any
            'plaidClientId' => $this->plaidClientId,
            'plaidSecret' => $this->plaidSecret,
            'plaidEnvironment' => $this->plaidEnvironment,
        ]);
    }
}
