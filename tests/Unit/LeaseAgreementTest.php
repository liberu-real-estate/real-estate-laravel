<?php

namespace Tests\Unit;

use App\Models\LeaseAgreement;
use App\Models\Property;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeaseAgreementTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_lease_agreement()
    {
        $agreementData = [
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addYear()->format('Y-m-d'),
            'rent_amount' => 1000,
            'security_deposit' => 2000,
            'status' => 'active',
        ];

        $agreement = LeaseAgreement::create($agreementData);

        $this->assertInstanceOf(LeaseAgreement::class, $agreement);
        $this->assertDatabaseHas('lease_agreements', [
            'rent_amount' => 1000,
            'security_deposit' => 2000,
            'status' => 'active',
        ]);
    }

    public function test_lease_agreement_relationships()
    {
        $agreement = LeaseAgreement::factory()->create();

        $this->assertInstanceOf(Property::class, $agreement->property);
        $this->assertNotNull($agreement->tenant_id);
    }

    public function test_lease_agreement_scopes()
    {
        LeaseAgreement::factory()->count(3)->create(['status' => 'active']);
        LeaseAgreement::factory()->count(2)->create(['status' => 'expired']);

        $this->assertCount(3, LeaseAgreement::where('status', 'active')->get());
        $this->assertCount(2, LeaseAgreement::where('status', 'expired')->get());
    }
}
