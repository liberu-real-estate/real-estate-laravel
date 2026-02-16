<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\SmartContract;
use App\Models\LeaseAgreement;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Team;
use App\Services\SmartContractService;
use App\Services\BlockchainService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SmartContractTest extends TestCase
{
    use RefreshDatabase;

    protected $smartContractService;
    protected $blockchainService;

    protected function setUp(): void
    {
        parent::setUp();
        
        config(['blockchain.network' => 'simulated']);
        
        $this->blockchainService = new BlockchainService();
        $this->smartContractService = new SmartContractService($this->blockchainService);
    }

    public function test_smart_contract_can_be_created()
    {
        $team = Team::factory()->create();
        $property = Property::factory()->create(['team_id' => $team->id]);
        $tenant = Tenant::factory()->create(['team_id' => $team->id]);
        $user = User::factory()->create();
        
        $leaseAgreement = LeaseAgreement::create([
            'tenant_id' => $tenant->id,
            'property_id' => $property->id,
            'team_id' => $team->id,
            'start_date' => now()->addDays(1),
            'end_date' => now()->addYear(),
            'monthly_rent' => 1500.00,
            'security_deposit' => 1500.00,
            'terms' => 'Standard lease terms',
            'content' => 'Lease agreement content',
        ]);

        $this->actingAs($user);
        
        $smartContract = $this->smartContractService->createSmartContract($leaseAgreement);

        $this->assertInstanceOf(SmartContract::class, $smartContract);
        $this->assertEquals('pending', $smartContract->status);
        $this->assertNotNull($smartContract->contract_address);
        $this->assertEquals($leaseAgreement->id, $smartContract->lease_agreement_id);
        $this->assertEquals($property->id, $smartContract->property_id);
        $this->assertEquals($tenant->id, $smartContract->tenant_id);
        $this->assertEquals(1500.00, (float) $smartContract->rent_amount);
        $this->assertFalse($smartContract->landlord_signed);
        $this->assertFalse($smartContract->tenant_signed);
    }

    public function test_smart_contract_can_be_signed_by_landlord()
    {
        $smartContract = $this->createActiveSmartContract();
        $landlord = User::find($smartContract->landlord_id);

        $this->actingAs($landlord);
        
        $transaction = $this->smartContractService->signContract($smartContract, $landlord, 'landlord');

        $smartContract->refresh();
        
        $this->assertTrue($smartContract->landlord_signed);
        $this->assertEquals('sign', $transaction->transaction_type);
    }

    public function test_smart_contract_can_be_signed_by_tenant()
    {
        $smartContract = $this->createActiveSmartContract();
        $tenant = Tenant::find($smartContract->tenant_id);

        $transaction = $this->smartContractService->signContract($smartContract, $tenant, 'tenant');

        $smartContract->refresh();
        
        $this->assertTrue($smartContract->tenant_signed);
        $this->assertEquals('sign', $transaction->transaction_type);
    }

    public function test_smart_contract_becomes_active_when_both_parties_sign()
    {
        $smartContract = $this->createActiveSmartContract();
        $landlord = User::find($smartContract->landlord_id);
        $tenant = Tenant::find($smartContract->tenant_id);

        $this->actingAs($landlord);
        $this->smartContractService->signContract($smartContract, $landlord, 'landlord');
        
        $smartContract->refresh();
        $this->assertEquals('pending', $smartContract->status);

        $this->smartContractService->signContract($smartContract, $tenant, 'tenant');
        
        $smartContract->refresh();
        $this->assertEquals('active', $smartContract->status);
        $this->assertTrue($smartContract->isActive());
        $this->assertNotNull($smartContract->activated_at);
    }

    public function test_rent_payment_can_be_processed()
    {
        $smartContract = $this->createActiveAndSignedSmartContract();
        $user = User::find($smartContract->landlord_id);

        $this->actingAs($user);
        
        $initialRentPaid = $smartContract->total_rent_paid;
        
        $transaction = $this->smartContractService->processRentPayment(
            $smartContract,
            (float) $smartContract->rent_amount,
            $user->id
        );

        $smartContract->refresh();
        
        $this->assertEquals('rent_payment', $transaction->transaction_type);
        $this->assertEquals($smartContract->rent_amount, $transaction->amount);
        $this->assertEquals($initialRentPaid + $smartContract->rent_amount, $smartContract->total_rent_paid);
        $this->assertEquals(1, $smartContract->rent_payments_count);
        $this->assertNotNull($smartContract->last_rent_payment);
    }

    public function test_smart_contract_can_be_terminated()
    {
        $smartContract = $this->createActiveAndSignedSmartContract();
        $user = User::find($smartContract->landlord_id);

        $this->actingAs($user);
        
        $transaction = $this->smartContractService->terminateContract($smartContract, $user->id);

        $smartContract->refresh();
        
        $this->assertEquals('terminated', $smartContract->status);
        $this->assertTrue($smartContract->isTerminated());
        $this->assertNotNull($smartContract->terminated_at);
        $this->assertEquals('terminate', $transaction->transaction_type);
    }

    public function test_blockchain_service_generates_agreement_hash()
    {
        $content = 'Test agreement content';
        $hash = $this->blockchainService->generateAgreementHash($content);

        $this->assertNotNull($hash);
        $this->assertEquals(64, strlen($hash)); // SHA-256 produces 64 character hex string
    }

    public function test_blockchain_service_verifies_agreement_hash()
    {
        $content = 'Test agreement content';
        $hash = $this->blockchainService->generateAgreementHash($content);

        $this->assertTrue($this->blockchainService->verifyAgreementHash($content, $hash));
        $this->assertFalse($this->blockchainService->verifyAgreementHash('Different content', $hash));
    }

    public function test_simulated_contract_deployment_returns_valid_data()
    {
        $abi = [];
        $bytecode = '0x123';
        $params = ['tenant' => '0xabc', 'rentAmount' => 1000];

        $result = $this->blockchainService->deploySmartContract($abi, $bytecode, $params);

        $this->assertTrue($result['success']);
        $this->assertStringStartsWith('0x', $result['contract_address']);
        $this->assertStringStartsWith('0x', $result['transaction_hash']);
        $this->assertEquals('simulated', $result['network']);
    }

    public function test_smart_contract_model_helper_methods()
    {
        $smartContract = $this->createActiveSmartContract();

        $this->assertTrue($smartContract->isPending());
        $this->assertFalse($smartContract->isActive());
        $this->assertFalse($smartContract->isTerminated());
        $this->assertFalse($smartContract->isFullySigned());
        $this->assertGreaterThan(0, $smartContract->getRemainingDays());
    }

    public function test_lease_agreement_can_check_smart_contract_status()
    {
        $team = Team::factory()->create();
        $property = Property::factory()->create(['team_id' => $team->id]);
        $tenant = Tenant::factory()->create(['team_id' => $team->id]);
        
        $leaseAgreement = LeaseAgreement::create([
            'tenant_id' => $tenant->id,
            'property_id' => $property->id,
            'team_id' => $team->id,
            'start_date' => now()->addDays(1),
            'end_date' => now()->addYear(),
            'monthly_rent' => 1500.00,
            'terms' => 'Standard lease terms',
            'content' => 'Lease agreement content',
        ]);

        $this->assertFalse($leaseAgreement->hasSmartContract());
        $this->assertTrue($leaseAgreement->canDeploySmartContract());

        $user = User::factory()->create();
        $this->actingAs($user);
        
        $smartContract = $this->smartContractService->createSmartContract($leaseAgreement);

        $leaseAgreement->refresh();
        $this->assertTrue($leaseAgreement->hasSmartContract());
    }

    // Helper methods

    protected function createActiveSmartContract()
    {
        $team = Team::factory()->create();
        $property = Property::factory()->create(['team_id' => $team->id]);
        $tenant = Tenant::factory()->create(['team_id' => $team->id]);
        $user = User::factory()->create();
        
        $leaseAgreement = LeaseAgreement::create([
            'tenant_id' => $tenant->id,
            'property_id' => $property->id,
            'team_id' => $team->id,
            'start_date' => now()->addDays(1),
            'end_date' => now()->addYear(),
            'monthly_rent' => 1500.00,
            'security_deposit' => 1500.00,
            'terms' => 'Standard lease terms',
            'content' => 'Lease agreement content',
        ]);

        $this->actingAs($user);
        
        return $this->smartContractService->createSmartContract($leaseAgreement);
    }

    protected function createActiveAndSignedSmartContract()
    {
        $smartContract = $this->createActiveSmartContract();
        $landlord = User::find($smartContract->landlord_id);
        $tenant = Tenant::find($smartContract->tenant_id);

        $this->smartContractService->signContract($smartContract, $landlord, 'landlord');
        $this->smartContractService->signContract($smartContract, $tenant, 'tenant');

        return $smartContract->fresh();
    }
}
