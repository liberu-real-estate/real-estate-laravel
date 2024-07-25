<?php

namespace Tests\Feature;

use App\Models\RentalApplication;
use App\Models\User;
use App\Services\BackgroundCheckService;
use App\Services\CreditReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class TenantScreeningTest extends TestCase
{
    use RefreshDatabase;

    public function testTenantScreeningProcess()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $backgroundCheckService = Mockery::mock(BackgroundCheckService::class);
        $backgroundCheckService->shouldReceive('check')->andReturn('passed');
        $this->app->instance(BackgroundCheckService::class, $backgroundCheckService);

        $creditReportService = Mockery::mock(CreditReportService::class);
        $creditReportService->shouldReceive('check')->andReturn('good');
        $this->app->instance(CreditReportService::class, $creditReportService);

        $response = $this->post('/rental-applications', [
            'property_id' => 1,
            'employment_status' => 'employed',
            'annual_income' => 50000,
            'ethereum_address' => '0x1234567890123456789012345678901234567890',
            'lease_start_date' => '2023-07-01',
            'lease_end_date' => '2024-06-30',
        ]);

        $response->assertRedirect(route('tenant.applications'));

        $this->assertDatabaseHas('rental_applications', [
            'tenant_id' => $user->id,
            'status' => 'pending',
            'background_check_status' => 'passed',
            'credit_report_status' => 'good',
        ]);

        $application = RentalApplication::where('tenant_id', $user->id)->first();
        $this->assertTrue($application->isScreeningComplete());
        $this->assertTrue($application->isScreeningPassed());
    }
}