<?php

namespace Tests\Unit;

use App\Services\LetsSafeScreeningService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LetsSafeScreeningServiceTest extends TestCase
{
    public function test_screen_tenant_success()
    {
        Http::fake([
            'api.letssafe.com/v1/screen' => Http::response([
                'credit_score' => 720,
                'background_check' => 'passed',
                'rental_history' => 'good',
            ], 200),
        ]);

        $service = new LetsSafeScreeningService();
        $result = $service->screenTenant(1);

        $this->assertEquals([
            'credit_score' => 720,
            'background_check' => 'passed',
            'rental_history' => 'good',
        ], $result);
    }

    public function test_screen_tenant_failure()
    {
        Http::fake([
            'api.letssafe.com/v1/screen' => Http::response(null, 500),
        ]);

        $service = new LetsSafeScreeningService();
        $result = $service->screenTenant(1);

        $this->assertNull($result);
    }
}