<?php

/**
 * Configures the Filament admin panel for the application.
 *
 * This file provides the service provider responsible for configuring the Filament admin panel,
 * including its resources, pages, widgets, and middleware.
 */

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

/**
 * Configures the Filament panel with resources, pages, and widgets.
 *
 * This method sets up the Filament panel, defining its path, resources, pages, widgets, and middleware.
 * It is responsible for the overall configuration of the Filament admin panel within the application.
 *
 * @param Panel $panel The Filament panel instance.
 * @return Panel The configured panel instance.
 */

// Configuration for the admin panel
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()->register()->resetPasswords()->verifyEmails()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->resource(TenantResource::class)
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ->registerResource(\App\Filament\Resources\BuyerResource::class)
            ->registerResource(\App\Filament\Resources\DocumentTemplateResource::class)
            ->registerResource(\App\Filament\Resources\DigitalSignatureResource::class)

            ->registerResource(\App\Filament\Resources\BranchResource::class)

            ->registerResource(\App\Filament\Resources\KeyLocationResource::class)

