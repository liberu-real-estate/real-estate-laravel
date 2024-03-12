&lt;?php

namespace Tests\Unit\Filament\Providers;

use Tests\TestCase;
use App\Providers\Filament\ContractorPanelProvider;
use Filament\Panel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class ContractorPanelProviderTest extends TestCase
{
    public function testMiddlewareConfiguration()
    {
        $panelMock = Mockery::mock(Panel::class)->makePartial();
        $panelMock->shouldReceive('middleware')->andReturnUsing(function ($middleware) {
            $this->assertContains(EncryptCookies::class, $middleware);
            $this->assertContains(AddQueuedCookiesToResponse::class, $middleware);
            $this->assertContains(StartSession::class, $middleware);
            $this->assertContains(AuthenticateSession::class, $middleware);
            $this->assertContains(ShareErrorsFromSession::class, $middleware);
            $this->assertContains(VerifyCsrfToken::class, $middleware);
            $this->assertContains(SubstituteBindings::class, $middleware);
            $this->assertContains(DisableBladeIconComponents::class, $middleware);
            $this->assertContains(DispatchServingFilamentEvent::class, $middleware);
            return $panelMock;
        });

        $provider = new ContractorPanelProvider();
        $provider->panel($panelMock);
    }

    public function testResourceDiscovery()
    {
        $panelMock = Mockery::mock(Panel::class)->makePartial();
        $panelMock->shouldReceive('discoverResources')->withArgs([app_path('Filament/Resources/Contractors'), 'App\\Filament\\Resources\\Contractors'])->andReturn($panelMock);

        $provider = new ContractorPanelProvider();
        $provider->panel($panelMock);

        $panelMock->shouldHaveReceived('discoverResources');
    }

    public function testPagesDiscovery()
    {
        $panelMock = Mockery::mock(Panel::class)->makePartial();
        $panelMock->shouldReceive('discoverPages')->withArgs([app_path('Filament/Pages/Contractors'), 'App\\Filament\\Pages\\Contractors'])->andReturn($panelMock);

        $provider = new ContractorPanelProvider();
        $provider->panel($panelMock);

        $panelMock->shouldHaveReceived('discoverPages');
    }

    public function testWidgetsDiscovery()
    {
        $panelMock = Mockery::mock(Panel::class)->makePartial();
        $panelMock->shouldReceive('discoverWidgets')->withArgs([app_path('Filament/Widgets/Contractors'), 'App\\Filament\\Widgets\\Contractors'])->andReturn($panelMock);

        $provider = new ContractorPanelProvider();
        $provider->panel($panelMock);

        $panelMock->shouldHaveReceived('discoverWidgets');
    }
}
