<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Providers\Filament\SellerPanelProvider;
use Filament\Panel;

class SellerPanelProviderTest extends TestCase
{
    public function testPanelConfiguration(): void
    {
        $provider = new SellerPanelProvider();
        $panel = $provider->panel(new Panel());

        $this->assertEquals('seller', $panel->getId());
        $this->assertEquals('seller', $panel->getPath());
        $this->assertEquals(['primary' => 'amber'], $panel->getColors());
        $this->assertEquals(app_path('Filament/Resources/Sellers'), $panel->getDiscoveredResourcesPath());
        $this->assertEquals(app_path('Filament/Pages/Sellers'), $panel->getDiscoveredPagesPath());
        $this->assertEquals(app_path('Filament/Widgets/Sellers'), $panel->getDiscoveredWidgetsPath());

        $expectedMiddleware = [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Filament\Http\Middleware\DisableBladeIconComponents::class,
            \Filament\Http\Middleware\DispatchServingFilamentEvent::class,
        ];

        foreach ($expectedMiddleware as $middleware) {
            $this->assertContains($middleware, $panel->getMiddleware());
        }

        $this->assertContains(\Filament\Http\Middleware\Authenticate::class, $panel->getAuthMiddleware());
    }
}
