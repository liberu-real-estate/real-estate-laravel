<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Inspection;

class InspectionStatus extends Component
{
    public $inspectionId;
    public $inspection;

    public function mount($inspectionId)
    {
        $this->inspectionId = $inspectionId;
        $this->loadInspection();
    }

    public function loadInspection()
    {
        $this->inspection = Inspection::with(['property', 'inspector', 'tenant'])->find($this->inspectionId);
    }

    public function updateStatus($newStatus)
    {
        $this->inspection->update(['status' => $newStatus]);
        $this->loadInspection();
        $this->emit('statusUpdated', $this->inspection->id);
    }

    public function render()
    {
        return view('livewire.inspection-status');
    }
}