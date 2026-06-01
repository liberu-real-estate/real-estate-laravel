<?php

namespace Tests\Unit;

use App\Services\BlockchainService;
use Tests\TestCase;

class BlockchainServiceTest extends TestCase
{
    private BlockchainService $service;

    protected function setUp(): void
    {
        parent::setUp();
        config(['blockchain.network' => 'simulated']);
        $this->service = new BlockchainService();
    }

    public function test_is_simulated_in_simulated_mode(): void
    {
        $this->assertTrue($this->service->isSimulated());
        $this->assertEquals('simulated', $this->service->getNetwork());
    }

    public function test_deploy_smart_contract_in_simulated_mode(): void
    {
        $result = $this->service->deploySmartContract([], '0x600', ['param1' => 'value1']);

        $this->assertArrayHasKey('contract_address', $result);
        $this->assertArrayHasKey('transaction_hash', $result);
        $this->assertStringStartsWith('0x', $result['contract_address']);
        $this->assertStringStartsWith('0x', $result['transaction_hash']);
    }

    public function test_call_contract_method_in_simulated_mode(): void
    {
        $result = $this->service->callContractMethod('0x1234', 'getStatus', []);

        $this->assertNotNull($result);
    }

    public function test_get_transaction_receipt_in_simulated_mode(): void
    {
        $result = $this->service->getTransactionReceipt('0xabcdef1234567890');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('transaction_hash', $result);
        $this->assertArrayHasKey('status', $result);
    }

    public function test_generate_agreement_hash(): void
    {
        $content = 'Test agreement content';
        $hash = $this->service->generateAgreementHash($content);

        $this->assertIsString($hash);
        $this->assertEquals(64, strlen($hash));
    }

    public function test_verify_agreement_hash_with_correct_hash(): void
    {
        $content = 'Test agreement content';
        $hash = $this->service->generateAgreementHash($content);

        $this->assertTrue($this->service->verifyAgreementHash($content, $hash));
    }

    public function test_verify_agreement_hash_with_wrong_hash(): void
    {
        $content = 'Test agreement content';
        $wrongHash = hash('sha256', 'different content');

        $this->assertFalse($this->service->verifyAgreementHash($content, $wrongHash));
    }

    public function test_send_transaction_in_simulated_mode(): void
    {
        $result = $this->service->sendTransaction('0x1234', 'transfer', [], 0);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('transaction_hash', $result);
    }
}
