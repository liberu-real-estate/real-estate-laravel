<?php

namespace Tests\Unit;

use App\Services\DocuSignService;
use Tests\TestCase;
use Mockery;

class DocuSignServiceTest extends TestCase
{
    protected $docuSignService;
    protected $mockApiClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockApiClient = Mockery::mock('DocuSign\eSign\Client\ApiClient');
        $this->docuSignService = new DocuSignService([
            'account_id' => 'test_account_id',
            'base_path' => 'https://demo.docusign.net/restapi',
        ]);
        $this->docuSignService->setApiClient($this->mockApiClient);
    }

    public function testCreateEnvelope()
    {
        $mockEnvelopesApi = Mockery::mock('DocuSign\eSign\Api\EnvelopesApi');
        $mockEnvelopeDefinition = Mockery::mock('DocuSign\eSign\Model\EnvelopeDefinition');
        $mockEnvelope = Mockery::mock('DocuSign\eSign\Model\Envelope');

        $this->mockApiClient->shouldReceive('getApiClient')->andReturn($mockEnvelopesApi);
        $mockEnvelopesApi->shouldReceive('createEnvelope')->andReturn($mockEnvelope);

        $result = $this->docuSignService->createEnvelope('test.pdf', 'test@example.com', 'Test User');

        $this->assertInstanceOf('DocuSign\eSign\Model\Envelope', $result);
    }

    public function testGetEnvelopeStatus()
    {
        $mockEnvelopesApi = Mockery::mock('DocuSign\eSign\Api\EnvelopesApi');
        $mockEnvelope = Mockery::mock('DocuSign\eSign\Model\Envelope');

        $this->mockApiClient->shouldReceive('getApiClient')->andReturn($mockEnvelopesApi);
        $mockEnvelopesApi->shouldReceive('getEnvelope')->andReturn($mockEnvelope);

        $result = $this->docuSignService->getEnvelopeStatus('test_envelope_id');

        $this->assertInstanceOf('DocuSign\eSign\Model\Envelope', $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}