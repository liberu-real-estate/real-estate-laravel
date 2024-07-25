<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Lease;
use App\Models\User;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeaseNotification;

class LeaseManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_lease_can_be_renewed()
    {
        $lease = Lease::factory()->create([
            'end_date' => now()->addDays(15),
            'status' => 'active',
        ]);

        $newEndDate = now()->addYear();
        $lease->renew($newEndDate);

        $this->assertEquals($newEndDate->toDateString(), $lease->fresh()->end_date->toDateString());
        $this->assertEquals('active', $lease->fresh()->status);
    }

    public function test_lease_can_be_terminated()
    {
        $lease = Lease::factory()->create([
            'end_date' => now()->addMonth(),
            'status' => 'active',
        ]);

        $terminationDate = now();
        $lease->terminate($terminationDate);

        $this->assertEquals($terminationDate->toDateString(), $lease->fresh()->end_date->toDateString());
        $this->assertEquals('terminated', $lease->fresh()->status);
    }

    public function test_renewal_notification_is_sent()
    {
        Notification::fake();

        $tenant = User::factory()->create();
        $property = Property::factory()->create();
        $lease = Lease::factory()->create([
            'tenant_id' => $tenant->id,
            'property_id' => $property->id,
            'end_date' => now()->addDays(15),
            'status' => 'active',
        ]);

        $job = new \App\Jobs\ScheduleLeaseNotifications();
        $job->handle();

        Notification::assertSentTo($tenant, LeaseNotification::class, function ($notification) use ($lease) {
            return $notification->lease->id === $lease->id && $notification->type === 'renewal';
        });
    }
}