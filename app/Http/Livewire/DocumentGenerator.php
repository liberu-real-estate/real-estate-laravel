<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\DocumentTemplate;
use App\Models\Lease;
use App\Models\Property;
use App\Services\DocumentGenerationService;

class DocumentGenerator extends Component
{
    public $selectedTemplate;
    public $customFields = [];
    public $generatedDocument;

    public function mount()
    {
        $this->templates = DocumentTemplate::all();
    }

    public function selectTemplate($templateId)
    {
        $this->selectedTemplate = DocumentTemplate::findOrFail($templateId);
        $this->customFields = $this->getCustomFieldsForTemplate();
    }

    public function generateDocument()
    {
        $service = new DocumentGenerationService();
        $this->generatedDocument = $service->generateDocument($this->selectedTemplate, $this->customFields);
    }

    private function getCustomFieldsForTemplate()
    {
        // Logic to extract custom fields from the template
        // This is a placeholder and should be implemented based on your template structure
        return [];
    }

    public function render()
    {
        return view('livewire.document-generator');
    }
}