<?php

namespace App\Providers\Filament;

use App\Filament\App\Pages;
use App\Filament\App\Pages\EditProfile;
use App\Http\Middleware\TeamsPermission;
use App\Listeners\CreatePersonalTeam;
use App\Listeners\SwitchTeam;
use App\Models\Team;
use Filament\Events\Auth\Registered;
use Filament\Events\TenantSet;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Support\Facades\Event;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel
            ->default()
            ->id('app')
            ->path('app')
            ->login()
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->tenant(Team::class)
            ->tenantMiddleware([
                TeamsPermission::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);

        Event::listen(Registered::class, CreatePersonalTeam::class);
        Event::listen(TenantSet::class, SwitchTeam::class);

        return $panel;
    }

    public function boot()
    {
        Filament::registerRenderHook(
            'panels::body.start',
            fn (): string => $this->checkDefaultTeam()
        );
    }

    private function checkDefaultTeam(): string
    {
        $user = auth()->user();
        if ($user && !$user->currentTeam) {
            $defaultTeam = $user->teams()->first();
            if ($defaultTeam) {
                $user->switchTeam($defaultTeam);
                return "<script>window.location.reload();</script>";
            }
        }
        return '';
    }
}