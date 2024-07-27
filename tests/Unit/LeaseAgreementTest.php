<?php

namespace Tests\Unit;

use App\Models\LeaseAgreement;
use App\Models\Property;
use App\Models\Tenant;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeaseAgreementTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_lease_agreement()
    {
        $agreementData = [
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'rent_amount' => 1000,
            'deposit_amount' => 2000,
            'status' => 'active',
        ];

        $agreement = LeaseAgreement::create($agreementData);

        $this->assertInstanceOf(LeaseAgreement::class, $agreement);
        $this->assertDatabaseHas('lease_agreements', $agreementData);
    }

    public function test_lease_agreement_relationships()
    {
        $agreement = LeaseAgreement::factory()->create();

        $this->assertInstanceOf(Property::class, $agreement->property);
        $this->assertInstanceOf(Tenant::class, $agreement->tenant);
    }

    public function test_lease_agreement_scopes()
    {
        LeaseAgreement::factory()->count(3)->create(['status' => 'active']);
        LeaseAgreement::factory()->count(2)->create(['status' => 'expired']);

        $this->assertCount(3, LeaseAgreement::active()->get());
        $this->assertCount(2, LeaseAgreement::expired()->get());
    }
}