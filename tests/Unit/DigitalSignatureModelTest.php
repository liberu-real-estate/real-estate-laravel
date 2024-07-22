<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\DigitalSignature;
use App\Models\User;
use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DigitalSignatureModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_relationship()
    {
        $user = User::factory()->create();
        $digitalSignature = DigitalSignature::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $digitalSignature->user);
        $this->assertEquals($user->id, $digitalSignature->user->id);
    }

    public function test_document_relationship()
    {
        $document = Document::factory()->create();
        $digitalSignature = DigitalSignature::factory()->create(['document_id' => $document->id]);

        $this->assertInstanceOf(Document::class, $digitalSignature->document);
        $this->assertEquals($document->id, $digitalSignature->document->id);
    }

    public function test_digital_signature_can_be_created()
    {
        $digitalSignature = DigitalSignature::factory()->create();

        $this->assertInstanceOf(DigitalSignature::class, $digitalSignature);
        $this->assertDatabaseHas('digital_signatures', ['id' => $digitalSignature->id]);
    }

    public function test_digital_signature_attributes()
    {
        $attributes = [
            'signature' => 'Test Signature',
            'signed_at' => now(),
        ];

        $digitalSignature = DigitalSignature::factory()->create($attributes);

        $this->assertEquals($attributes['signature'], $digitalSignature->signature);
        $this->assertEquals($attributes['signed_at'], $digitalSignature->signed_at);
    }
}
