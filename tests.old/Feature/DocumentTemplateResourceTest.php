<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\DocumentTemplate;

class DocumentTemplateResourceTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function testCreateDocumentTemplate()
    {
        $data = [
            'name' => 'Sample Template',
            'file_path' => 'templates/sample_template.docx',
            'description' => 'This is a sample document template.'
        ];

        $response = $this->post(route('document-templates.store'), $data);

        $response->assertRedirect(route('document-templates.index'));
        $this->assertDatabaseHas('document_templates', $data);
    }

    public function testEditDocumentTemplate()
    {
        $template = DocumentTemplate::create([
            'name' => 'Original Template',
            'file_path' => 'templates/original_template.docx',
            'description' => 'Original description.'
        ]);

        $updatedData = [
            'name' => 'Updated Template',
            'file_path' => 'templates/updated_template.docx',
            'description' => 'Updated description.'
        ];

        $response = $this->post(route('document-templates.update', ['document_template' => $template->id]), $updatedData);

        $response->assertRedirect(route('document-templates.index'));
        $this->assertDatabaseHas('document_templates', $updatedData);
    }

    public function testListDocumentTemplates()
    {
        $template = DocumentTemplate::create([
            'name' => 'Listed Template',
            'file_path' => 'templates/listed_template.docx',
            'description' => 'To be listed.'
        ]);

        $response = $this->get(route('document-templates.index'));

        $response->assertOk();
        $response->assertViewHas('document_templates', function ($viewTemplates) use ($template) {
            return $viewTemplates->contains($template);
        });
    }
}
