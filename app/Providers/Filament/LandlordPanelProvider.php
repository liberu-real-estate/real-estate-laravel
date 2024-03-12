<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class LandlordPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('landlord')
            ->path('landlord')
            ->register()->resetPasswords()->verifyEmails()
            ->configureLogin($panel)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources/Landlords'), for: 'App\\Filament\\Resources\\Landlords')
            ->discoverPages(in: app_path('Filament/Pages/Landlords'), for: 'App\\Filament\\Pages\\Landlords')
            ->pages([
                // Assuming Pages\LandlordDashboard exists for landlord-specific dashboard
                Pages\LandlordDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets/Landlords'), for: 'App\\Filament\\Widgets\\Landlords')
            ->widgets([
                // Assuming Widgets\LandlordAccountWidget exists for landlord-specific account widget
                Widgets\LandlordAccountWidget::class,
                // Assuming Widgets\LandlordInfoWidget exists for displaying landlord-specific information
                Widgets\LandlordInfoWidget::class,
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
    protected function configureLogin(Panel $panel): Panel
    {
        // Define the login logic here to make it more modular
        return $panel->login();
    }
