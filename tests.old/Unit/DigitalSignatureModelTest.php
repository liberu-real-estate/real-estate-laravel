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

    public function testUserRelationship()
    {
        $user = User::factory()->create();
        $digitalSignature = DigitalSignature::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $digitalSignature->user);
        $this->assertEquals($user->id, $digitalSignature->user->id);
    }

    public function testDocumentRelationship()
    {
        $document = Document::factory()->create();
        $digitalSignature = DigitalSignature::factory()->create(['document_id' => $document->id]);

        $this->assertInstanceOf(Document::class, $digitalSignature->document);
        $this->assertEquals($document->id, $digitalSignature->document->id);
    }
}
