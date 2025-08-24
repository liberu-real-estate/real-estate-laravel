<?php

namespace App\Http\Livewire;

use Exception;
use Livewire\Component;
use App\Services\AIAssistantService;
use App\Models\Property;
use App\Models\MaintenanceRequest;
use App\Models\User;

class AIAssistant extends Component
{
    public $input;
    public $response;
    public $context = 'general';
    public $selectedProperty;
    public $selectedTenant;
    public $selectedMaintenanceRequest;

    protected $aiAssistant;

    public function boot(AIAssistantService $aiAssistant)
    {
        $this->aiAssistant = $aiAssistant;
    }

    public function render()
    {
        return view('livewire.ai-assistant', [
            'properties' => Property::all(),
            'tenants' => User::role('tenant')->get(),
            'maintenanceRequests' => MaintenanceRequest::all(),
        ]);
    }

    public function generateResponse()
    {
        $this->validate([
            'input' => 'required|string',
        ]);

        try {
            $this->response = $this->aiAssistant->generateResponse($this->input, $this->context);
        } catch (Exception $e) {
            $this->response = "Error: " . $e->getMessage();
        }
    }

    public function scheduleMaintenance()
    {
        $this->validate([
            'selectedMaintenanceRequest' => 'required|exists:maintenance_requests,id',
        ]);

        $maintenanceRequest = MaintenanceRequest::find($this->selectedMaintenanceRequest);
        $this->response = $this->aiAssistant->scheduleMaintenance($maintenanceRequest);
    }

    public function generateFinancialReport()
    {
        $this->validate([
            'selectedProperty' => 'required|exists:properties,id',
        ]);

        $property = Property::find($this->selectedProperty);
        $this->response = $this->aiAssistant->generateFinancialReport($property);
    }

    public function generateTenantCommunication()
    {
        $this->validate([
            'selectedTenant' => 'required|exists:users,id',
            'input' => 'required|string',
        ]);

        $tenant = User::find($this->selectedTenant);
        $this->response = $this->aiAssistant->generateTenantCommunication($tenant, $this->input);
    }
}