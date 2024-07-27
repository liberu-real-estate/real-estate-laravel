<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Filament\Resources\DigitalSignatureResource;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DigitalSignatureResourceTest extends TestCase
{
    use RefreshDatabase;

    public function testResourceIsCorrectlyRegistered()
    {
        $this->assertResourceIsRegistered(DigitalSignatureResource::class);
    }

    public function testDigitalSignaturesCanBeListed()
    {
        $this->actingAsUser();
        $this->visitResourcePage(DigitalSignatureResource::class);
        $this->assertPageContainsResourceList();
    }

    public function testDigitalSignatureCanBeCreated()
    {
        $this->actingAsUser();
        $this->visitResourceCreatePage(DigitalSignatureResource::class);
        $this->fillForm([
            'user_id' => $user->id,
            'document_id' => $document->id,
        ]);
        $this->submitForm();
        $this->assertDatabaseHas('digital_signatures', [
            'user_id' => $user->id,
            'document_id' => $document->id,
        ]);
    }

    public function testDigitalSignatureCanBeEdited()
    {
        $digitalSignature = DigitalSignature::factory()->create();
        $this->actingAsUser();
        $this->visitResourceEditPage(DigitalSignatureResource::class, $digitalSignature->id);
        $this->fillForm([
            'user_id' => $newUser->id,
        ]);
        $this->submitForm();
        $this->assertDatabaseHas('digital_signatures', [
            'id' => $digitalSignature->id,
            'user_id' => $newUser->id,
        ]);
    }

    public function testDigitalSignatureCanBeDeleted()
    {
        $digitalSignature = DigitalSignature::factory()->create();
        $this->actingAsUser();
        $this->visitResourceDeletePage(DigitalSignatureResource::class, $digitalSignature->id);
        $this->submitForm();
        $this->assertDatabaseMissing('digital_signatures', [
            'id' => $digitalSignature->id,
        ]);
    }
}
