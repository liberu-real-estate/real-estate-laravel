<?php

use Tests\TestCase;
use App\Models\DocumentTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DocumentTemplateTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateDocumentTemplate()
    {
        $templateData = [
            'name' => 'Test Template',
            'file_path' => 'templates/test_template.docx',
            'description' => 'A test template for documents.'
        ];

        DocumentTemplate::create($templateData);

        $this->assertDatabaseHas('document_templates', $templateData);
    }

    public function testUpdateDocumentTemplate()
    {
        $template = DocumentTemplate::create([
            'name' => 'Initial Template',
            'file_path' => 'templates/initial_template.docx',
            'description' => 'Initial template description.'
        ]);

        $updatedData = [
            'name' => 'Updated Template',
            'file_path' => 'templates/updated_template.docx',
            'description' => 'Updated template description.'
        ];

        $template->update($updatedData);

        $this->assertDatabaseHas('document_templates', $updatedData);
    }

    public function testRetrieveDocumentTemplate()
    {
        $templateData = [
            'name' => 'Retrieved Template',
            'file_path' => 'templates/retrieved_template.docx',
            'description' => 'A template to be retrieved.'
        ];

        $template = DocumentTemplate::create($templateData);

        $retrievedTemplate = DocumentTemplate::find($template->id);

        $this->assertEquals($templateData['name'], $retrievedTemplate->name);
        $this->assertEquals($templateData['file_path'], $retrievedTemplate->file_path);
        $this->assertEquals($templateData['description'], $retrievedTemplate->description);
    }
}
