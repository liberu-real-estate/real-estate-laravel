<?php

namespace Tests\Unit;

use App\Services\QRCodeService;
use App\Models\Property;
use App\Models\Team;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QRCodeServiceTest extends TestCase
{
    use RefreshDatabase;

    private QRCodeService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new QRCodeService();
    }

    /** @test */
    public function it_generates_qr_code_url_for_content()
    {
        $url = $this->service->generateQRCodeUrl('https://example.com/property/1', 200);

        $this->assertStringContainsString('chart.googleapis.com', $url);
        $this->assertStringContainsString('cht=qr', $url);
        $this->assertStringContainsString('200x200', $url);
    }

    /** @test */
    public function it_generates_qr_code_data_with_defaults()
    {
        $data = $this->service->generateQRCodeData('https://example.com/property/1');

        $this->assertArrayHasKey('url', $data);
        $this->assertArrayHasKey('content', $data);
        $this->assertArrayHasKey('size', $data);
        $this->assertEquals(200, $data['size']);
        $this->assertEquals('https://example.com/property/1', $data['content']);
    }

    /** @test */
    public function it_throws_exception_for_empty_content()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('QR code content cannot be empty');

        $this->service->generateQRCodeUrl('');
    }

    /** @test */
    public function it_throws_exception_for_invalid_size_too_small()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('QR code size must be between 50 and 1000 pixels');

        $this->service->generateQRCodeUrl('https://example.com', 20);
    }

    /** @test */
    public function it_throws_exception_for_invalid_size_too_large()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('QR code size must be between 50 and 1000 pixels');

        $this->service->generateQRCodeUrl('https://example.com', 2000);
    }

    /** @test */
    public function it_generates_qr_code_for_property()
    {
        $team = Team::create(['name' => 'Test Team', 'user_id' => 1, 'personal_team' => false]);
        $user = User::factory()->create(['current_team_id' => $team->id]);
        $property = Property::factory()->create(['user_id' => $user->id, 'team_id' => $team->id]);

        $data = $this->service->generatePropertyQRCodeData($property, 300);

        $this->assertArrayHasKey('url', $data);
        $this->assertArrayHasKey('property_url', $data);
        $this->assertArrayHasKey('property_id', $data);
        $this->assertEquals($property->id, $data['property_id']);
        $this->assertEquals(300, $data['size']);
        $this->assertStringContainsString((string) $property->id, $data['property_url']);
    }

    /** @test */
    public function it_generates_qr_code_url_for_property()
    {
        $team = Team::create(['name' => 'Test Team', 'user_id' => 1, 'personal_team' => false]);
        $user = User::factory()->create(['current_team_id' => $team->id]);
        $property = Property::factory()->create(['user_id' => $user->id, 'team_id' => $team->id]);

        $url = $this->service->generatePropertyQRCodeUrl($property, 200);

        $this->assertStringContainsString('chart.googleapis.com', $url);
        $this->assertStringContainsString('cht=qr', $url);
    }
}
