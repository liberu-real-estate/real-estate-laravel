<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Models\User;

class CustomReportForm extends Component
{
    public $reportType = '';
    public $startDate;
    public $endDate;
    public $selectedProperties = [];
    public $selectedTenants = [];

    public function mount()
    {
        $this->startDate = now()->subMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.custom-report-form', [
            'properties' => Property::all(),
            'tenants' => User::role('tenant')->get(),
        ]);
    }

    public function generateReport()
    {
        $this->validate([
            'reportType' => 'required|string',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after:startDate',
            'selectedProperties' => 'nullable|array',
            'selectedTenants' => 'nullable|array',
        ]);

        // Emit an event to notify the parent component to generate the report
        $this->emit('generateReport', [
            'report_type' => $this->reportType,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'properties' => $this->selectedProperties,
            'tenants' => $this->selectedTenants,
        ]);
    }
}