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

class SellerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        public function panel(Panel $panel): Panel
        {
        $panel = $this->setupPanel($panel);
        $panel = $this->discoverResources($panel);
        $panel = $this->discoverPages($panel);
        $panel = $this->discoverWidgets($panel);
        $panel = $this->setMiddleware($panel);
        $panel = $this->setAuthMiddleware($panel);
        return $panel;
        }
    private function setupPanel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('seller')
            ->path('seller')
            ->login()->register()->resetPasswords()->verifyEmails()
            ->colors([
                'primary' => Color::Amber,
            ]);
    }

    private function discoverResources(Panel $panel): Panel
    {
        return $panel->discoverResources(in: app_path('Filament/Resources/Sellers'), for: 'App\\Filament\\Resources\\Sellers');
    }

    private function discoverPages(Panel $panel): Panel
    {
        return $panel->discoverPages(in: app_path('Filament/Pages/Sellers'), for: 'App\\Filament\\Pages\\Sellers');
    }

    private function discoverWidgets(Panel $panel): Panel
    {
        return $panel->discoverWidgets(in: app_path('Filament/Widgets/Sellers'), for: 'App\\Filament\\Widgets\\Sellers');
    }

    private function setMiddleware(Panel $panel): Panel
    {
        return $panel->middleware([
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DisableBladeIconComponents::class,
            DispatchServingFilamentEvent::class,
        ]);
    }

    private function setAuthMiddleware(Panel $panel): Panel
    {
        return $panel->authMiddleware([
            Authenticate::class,
        ]);
    }
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
