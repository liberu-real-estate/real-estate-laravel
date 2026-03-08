<?php

namespace Tests\Unit;

use App\Services\DocuSignService;
use Tests\TestCase;
use Mockery;

class DocuSignServiceTest extends TestCase
{
    protected $docuSignService;
    protected $mockEnvelopesApi;

    protected function setUp(): void
    {
        parent::setUp();

        $this->docuSignService = new DocuSignService([
            'account_id' => 'test_account_id',
            'base_path' => 'https://demo.docusign.net/restapi',
        ]);

        $this->mockEnvelopesApi = Mockery::mock('DocuSign\eSign\Api\EnvelopesApi');
        $this->docuSignService->setEnvelopesApi($this->mockEnvelopesApi);
    }

    public function testCreateEnvelope()
    {
        $mockEnvelope = Mockery::mock('DocuSign\eSign\Model\Envelope');

        $this->mockEnvelopesApi->shouldReceive('createEnvelope')->andReturn($mockEnvelope);

        $result = $this->docuSignService->createEnvelope('test.pdf', 'test@example.com', 'Test User');

        $this->assertInstanceOf('DocuSign\eSign\Model\Envelope', $result);
    }

    public function testGetEnvelopeStatus()
    {
        $mockEnvelope = Mockery::mock('DocuSign\eSign\Model\Envelope');

        $this->mockEnvelopesApi->shouldReceive('getEnvelope')->andReturn($mockEnvelope);

        $result = $this->docuSignService->getEnvelopeStatus('test_envelope_id');

        $this->assertInstanceOf('DocuSign\eSign\Model\Envelope', $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
