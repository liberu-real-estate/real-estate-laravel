<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ContractorPanelProvider extends PanelProvider
/**
 * ContractorPanelProvider configures the Filament panel for contractor users, defining resources, pages, and widgets.
 */
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('contractor')
            ->path('contractor')
            ->login()->register()->resetPasswords()->verifyEmails()
            ->discoverResources(in: app_path('Filament/Resources/Contractors'), for: 'App\\Filament\\Resources\\Contractors')
            ->discoverPages(in: app_path('Filament/Pages/Contractors'), for: 'App\\Filament\\Pages\\Contractors')
            ->discoverWidgets(in: app_path('Filament/Widgets/Contractors'), for: 'App\\Filament\\Widgets\\Contractors')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
    /**
     * Configures the Filament panel for contractors.
     * 
     * @param Panel $panel The panel instance.
     * @return Panel The configured panel instance for contractors.
     */
