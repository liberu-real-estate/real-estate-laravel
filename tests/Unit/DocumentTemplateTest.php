<?php

namespace Tests\Unit;

use App\Models\DocumentTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentTemplateTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_document_template()
    {
        $documentTemplate = DocumentTemplate::factory()->create();

        $this->assertInstanceOf(DocumentTemplate::class, $documentTemplate);
        $this->assertDatabaseHas('document_templates', ['id' => $documentTemplate->id]);
    }

    public function test_document_template_relationships()
    {
        // DocumentTemplate has no relationships to test
        $this->expectNotToPerformAssertions();
    }

    public function test_document_template_scopes()
    {
        // DocumentTemplate has no custom scopes to test
        $this->expectNotToPerformAssertions();
    }
}