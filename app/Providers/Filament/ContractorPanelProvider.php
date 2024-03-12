<?php

/**
 * ContractorPanelProvider configures the Filament panel for contractors.
 * 
 * It sets up the panel with specific middleware, authentication, and discovers resources, pages, and widgets
 * related to contractors.
 */

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
 * Configures the Filament panel for contractors.
 *
 * This function sets up middleware, authentication, and discovers resources, pages, and widgets
 * for the contractor panel.
 *
 * @param Panel $panel
 * @return Panel
 */
class ContractorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('contractor')
            ->path('contractor')
            ->login()
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
