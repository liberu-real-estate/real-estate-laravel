<?php

namespace App\Http\Livewire;

use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Services\NotificationService;
use Livewire\Component;

class MaintenanceRequestForm extends Component
{
    public $title;
    public $description;
    public $property_id;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'property_id' => 'required|exists:properties,id',
    ];

    public function submit(NotificationService $notificationService)
    {
        $this->validate();

        $maintenanceRequest = MaintenanceRequest::create([
            'title' => $this->title,
            'description' => $this->description,
            'status' => 'pending',
            'requested_date' => now(),
            'tenant_id' => auth()->user()->id,
            'property_id' => $this->property_id,
        ]);

        $notificationService->notifyTenantRequestSubmitted(auth()->user(), $maintenanceRequest);

        $this->reset(['title', 'description', 'property_id']);
        session()->flash('message', 'Maintenance request submitted successfully.');
    }

    public function render()
    {
        $properties = Property::where('tenant_id', auth()->user()->id)->get();
        return view('livewire.maintenance-request-form', compact('properties'));
    }
}