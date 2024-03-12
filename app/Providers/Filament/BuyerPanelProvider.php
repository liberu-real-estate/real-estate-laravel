/**
 * Configures the Filament panel for the buyer role.
 *
 * This class is responsible for setting up the Filament panel specific to buyers, including defining its appearance,
 * resources, pages, widgets, and middleware configurations to ensure a tailored administrative experience for buyers.
 */
<?php

namespace App\Providers\Filament;

use Filament\PanelProvider;
use Filament\Panel;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class BuyerPanelProvider extends PanelProvider
{
    /**
     * Configures and returns the Filament panel for the buyer role.
     *
     * @param Panel $panel The initial panel instance.
     * @return Panel The configured panel instance for the buyer role, including appearance settings, resources, pages, widgets, and middleware.
     */
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('buyer')
            ->path('buyer')
            ->login()->register()->resetPasswords()->verifyEmails()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources/Buyers'), for: 'App\\Filament\\Resources\\Buyers')
            ->discoverPages(in: app_path('Filament/Pages/Buyers'), for: 'App\\Filament\\Pages\\Buyers')
            ->pages([
                Pages\BuyerDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets/Buyers'), for: 'App\\Filament\\Widgets\\Buyers')
            ->widgets([
                Widgets\BuyerAccountWidget::class,
            ])
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
