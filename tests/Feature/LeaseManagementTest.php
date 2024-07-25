<?php

namespace Tests\Feature;

use App\Models\Lease;
use App\Models\User;
use App\Models\Property;
use App\Services\LeaseNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class LeaseManagementTest extends TestCase
{
    use RefreshDatabase;

    public function testLeaseRenewal()
    {
        $lease = Lease::factory()->create(['status' => 'active']);
        $originalEndDate = $lease->end_date;

        $notificationService = Mockery::mock(LeaseNotificationService::class);
        $notificationService->shouldReceive('sendRenewalNotification')->once();
        $this->app->instance(LeaseNotificationService::class, $notificationService);

        $lease->renew();

        $this->assertEquals($originalEndDate->addYear(), $lease->end_date);
        $this->assertEquals('active', $lease->status);
    }

    public function testLeaseTermination()
    {
        $lease = Lease::factory()->create(['status' => 'active']);

        $notificationService = Mockery::mock(LeaseNotificationService::class);
        $notificationService->shouldReceive('sendTerminationNotification')->once();
        $this->app->instance(LeaseNotificationService::class, $notificationService);

        $lease->terminate();

        $this->assertEquals('terminated', $lease->status);
        $this->assertTrue($lease->end_date->isToday());
    }
}