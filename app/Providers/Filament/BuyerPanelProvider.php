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
    public function panel(Panel $panel): Panel
    {
        $panel = $this->setupPanelDefaults($panel);
        $panel = $this->definePanelColors($panel);
        $panel = $this->discoverPanelResources($panel);
        $panel = $this->setupPanelPages($panel);
        $panel = $this->discoverPanelWidgets($panel);
        $panel = $this->configurePanelMiddleware($panel);
        $panel = $this->configurePanelAuthMiddleware($panel);
        return $panel;
    protected function setupPanelDefaults(Panel $panel): Panel
    {
        return $panel->default()
            ->id('buyer')
            ->path('buyer')
            ->login();
    }

    protected function definePanelColors(Panel $panel): Panel
    {
        return $panel->colors([
            'primary' => Color::Amber,
        ]);
    }

    protected function discoverPanelResources(Panel $panel): Panel
    {
        return $panel->discoverResources(in: app_path('Filament/Resources/Buyers'), for: 'App\\Filament\\Resources\\Buyers');
    }

    protected function setupPanelPages(Panel $panel): Panel
    {
        return $panel->pages([
            Pages\BuyerDashboard::class,
        ])->discoverPages(in: app_path('Filament/Pages/Buyers'), for: 'App\\Filament\\Pages\\Buyers');
    }

    protected function discoverPanelWidgets(Panel $panel): Panel
    {
        return $panel->widgets([
            Widgets\BuyerAccountWidget::class,
        ])->discoverWidgets(in: app_path('Filament/Widgets/Buyers'), for: 'App\\Filament\\Widgets\\Buyers');
    }

    protected function configurePanelMiddleware(Panel $panel): Panel
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

    protected function configurePanelAuthMiddleware(Panel $panel): Panel
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
