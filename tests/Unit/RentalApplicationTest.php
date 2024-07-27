<?php

namespace Tests\Unit;

use App\Models\RentalApplication;
use App\Models\Property;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RentalApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_rental_application()
    {
        $property = Property::factory()->create();
        $user = User::factory()->create();

        $applicationData = [
            'property_id' => $property->id,
            'user_id' => $user->id,
            'status' => 'pending',
            'move_in_date' => now()->addMonth(),
            'monthly_income' => 5000,
            'employment_status' => 'employed',
        ];

        $application = RentalApplication::create($applicationData);

        $this->assertInstanceOf(RentalApplication::class, $application);
        $this->assertDatabaseHas('rental_applications', ['id' => $application->id]);
    }

    public function test_rental_application_relationships()
    {
        $application = RentalApplication::factory()->create();

        $this->assertInstanceOf(Property::class, $application->property);
        $this->assertInstanceOf(User::class, $application->user);
    }

    public function test_rental_application_scope()
    {
        $pendingApplication = RentalApplication::factory()->create(['status' => 'pending']);
        $approvedApplication = RentalApplication::factory()->create(['status' => 'approved']);

        $pendingApplications = RentalApplication::pending()->get();
        $approvedApplications = RentalApplication::approved()->get();

        $this->assertCount(1, $pendingApplications);
        $this->assertCount(1, $approvedApplications);
        $this->assertEquals($pendingApplication->id, $pendingApplications->first()->id);
        $this->assertEquals($approvedApplication->id, $approvedApplications->first()->id);
    }
}