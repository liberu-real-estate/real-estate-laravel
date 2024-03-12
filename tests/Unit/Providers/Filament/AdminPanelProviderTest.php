&lt;?php

namespace Tests\Unit\Providers\Filament;

use Tests\TestCase;
use Mockery;
use App\Providers\Filament\AdminPanelProvider;
use Filament\Panel;

class AdminPanelProviderTest extends TestCase
{
    public function testLoginMethodConfiguration()
    {
        $panelMock = Mockery::mock(Panel::class);
        $panelMock->shouldReceive('login')->once()->andReturnSelf();

        $provider = new AdminPanelProvider();
        $provider->panel($panelMock);

        $this->addToAssertionCount(Mockery::getContainer()->mockery_getExpectationCount());
    }

    public function testRegisterMethodConfiguration()
    {
        $panelMock = Mockery::mock(Panel::class);
        $panelMock->shouldReceive('register')->once()->andReturnSelf();

        $provider = new AdminPanelProvider();
        $provider->panel($panelMock);

        $this->addToAssertionCount(Mockery::getContainer()->mockery_getExpectationCount());
    }

    public function testResetPasswordsMethodConfiguration()
    {
        $panelMock = Mockery::mock(Panel::class);
        $panelMock->shouldReceive('resetPasswords')->once()->andReturnSelf();

        $provider = new AdminPanelProvider();
        $provider->panel($panelMock);

        $this->addToAssertionCount(Mockery::getContainer()->mockery_getExpectationCount());
    }

    public function testVerifyEmailsMethodConfiguration()
    {
        $panelMock = Mockery::mock(Panel::class);
        $panelMock->shouldReceive('verifyEmails')->once()->andReturnSelf();

        $provider = new AdminPanelProvider();
        $provider->panel($panelMock);

        $this->addToAssertionCount(Mockery::getContainer()->mockery_getExpectationCount());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
