<?php

namespace Tests\Feature;

use App\Models\Lease;
use App\Models\User;
use App\Models\Property;
use App\Notifications\LeaseNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class LeaseManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_lease_can_be_created()
    {
        $property = Property::factory()->create();
        $tenant = User::factory()->create();

        $leaseData = [
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'rent_amount' => 1000,
            'status' => 'active',
        ];

        $lease = Lease::create($leaseData);

        $this->assertDatabaseHas('leases', $leaseData);
    }

    public function test_lease_can_be_renewed()
    {
        $lease = Lease::factory()->create([
            'end_date' => now()->addDays(20),
            'rent_amount' => 1000,
        ]);

        $newEndDate = $lease->end_date->addYear();
        $newRentAmount = 1030;

        $lease->renew($newEndDate, $newRentAmount);

        $this->assertEquals($newEndDate, $lease->end_date);
        $this->assertEquals($newRentAmount, $lease->rent_amount);
        $this->assertEquals('active', $lease->status);
    }

    public function test_lease_can_be_terminated()
    {
        $lease = Lease::factory()->create(['status' => 'active']);

        $terminationDate = now();
        $lease->terminate($terminationDate);

        $this->assertEquals($terminationDate, $lease->end_date);
        $this->assertEquals('terminated', $lease->status);
    }

    public function test_lease_notification_is_sent()
    {
        Notification::fake();

        $lease = Lease::factory()->create([
            'end_date' => now()->addDays(20),
            'status' => 'active',
        ]);

        $job = new \App\Jobs\CheckLeaseNotifications();
        $job->handle();

        Notification::assertSentTo(
            $lease->tenant,
            LeaseNotification::class,
            function ($notification) use ($lease) {
                return $notification->lease->id === $lease->id;
            }
        );
    }
}