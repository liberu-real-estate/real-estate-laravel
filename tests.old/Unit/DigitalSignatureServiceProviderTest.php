<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Application;
use App\Services\DigitalSignatureService;

class DigitalSignatureServiceProviderTest extends TestCase
{
    public function testDigitalSignatureServiceSingletonRegistration()
    {
        $digitalSignatureService = $this->app->make('DigitalSignatureService');

        $this->assertInstanceOf(DigitalSignatureService::class, $digitalSignatureService);

        $expectedApiKey = config('services.digital_signature.api_key');
        $expectedEndpoint = config('services.digital_signature.endpoint');

        $this->assertEquals($expectedApiKey, $digitalSignatureService->getApiKey());
        $this->assertEquals($expectedEndpoint, $digitalSignatureService->getEndpoint());
    }
}
