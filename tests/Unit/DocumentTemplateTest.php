<?php

namespace Tests\Unit;

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

        // Test new document template types
        $newTemplates = [
            'notice_to_enter' => 'Notice to Enter',
            'notice_of_rent_increase' => 'Notice of Rent Increase',
            'tenant_welcome_letter' => 'Tenant Welcome Letter',
            'guarantor_agreement' => 'Guarantor Agreement'
        ];

        foreach ($newTemplates as $type => $name) {
            $template = DocumentTemplate::findOrCreateTemplate(
                $type,
                $name,
                "$name template",
                "document_templates.$type"
            );

            $this->assertDatabaseHas('document_templates', [
                'type' => $type,
                'name' => $name,
                'description' => "$name template",
                'file_path' => "document_templates.$type"
            ]);
        }
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
