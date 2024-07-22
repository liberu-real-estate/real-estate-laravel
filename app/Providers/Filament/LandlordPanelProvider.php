<?php

namespace App\Providers\Filament;

use App\Filament\App\Pages;
use App\Filament\App\Pages\EditProfile;
use App\Filament\App\Pages\CreateTeam;
use App\Filament\App\Pages\EditTeam;
use App\Filament\App\Pages\Tenant\Profile;
use App\Http\Middleware\TeamsPermission;
use App\Http\Middleware\AssignDefaultTeam;
use App\Listeners\CreatePersonalTeam;
use App\Listeners\SwitchTeam;
use App\Models\Team;
use Filament\Events\Auth\Registered;
use Filament\Events\TenantSet;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages\Dashboard;
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
use Illuminate\Support\Facades\Event;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Jetstream;

class LandlordPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel
            ->default()
            ->id('landlord')
            ->path('landlord')
            ->login([AuthenticatedSessionController::class, 'create'])
            ->loginRouteSlug('login')
            ->homeUrl('/landlord')
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->colors([
                'primary' => Color::Gray,
            ]);

        if (Features::hasTeamFeatures()) {
            $panel
                ->tenant(Team::class, ownershipRelationship: 'team')
             //   ->tenantRoutePrefix('/{tenant}')
                ->tenantMiddleware([
                    AssignDefaultTeam::class,
                ])
                ->tenantRegistration(CreateTeam::class)
                ->tenantProfile(EditTeam::class);
        }

        $panel
            ->discoverResources(in: app_path('Filament/Landlord/Resources'), for: 'App\\Filament\\Landlord\\Resources')
            ->discoverPages(in: app_path('Filament/Landlord/Pages'), for: 'App\\Filament\\Landlord\\Pages')
            ->pages([
                Dashboard::class,
                Pages\EditProfile::class,
                Profile::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Landlord/Widgets/Home'), for: 'App\\Filament\\Landlord\\Widgets\\Home')
            ->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
                TeamsPermission::class,
            ])
            ->plugins([
                // \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
            ]);

        return $panel;
    }

    public function boot()
    {

        /**
         * Disable Fortify routes.
         */
        Fortify::$registersRoutes = false;

        /**
         * Disable Jetstream routes.
         */
        Jetstream::$registersRoutes = false;
    }

    // This method has been removed

    public function shouldRegisterMenuItem(): bool
    {
        return true; //auth()->user()?->hasVerifiedEmail() && Filament::hasTenancy() && Filament::getTenant();
    }


}
