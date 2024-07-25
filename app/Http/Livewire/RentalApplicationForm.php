<?php

namespace App\Http\Livewire;

use App\Models\Property;
use App\Models\RentalApplication;
use Livewire\Component;

class RentalApplicationForm extends Component
{
    public $property_id;
    public $employment_status;
    public $annual_income;
    public $ethereum_address;
    public $lease_start_date;
    public $lease_end_date;

    protected $rules = [
        'property_id' => 'required|exists:properties,id',
        'employment_status' => 'required|string',
        'annual_income' => 'required|numeric|min:0',
        'ethereum_address' => 'required|string',
        'lease_start_date' => 'required|date|after:today',
        'lease_end_date' => 'required|date|after:lease_start_date',
    ];

    public function mount($property_id)
    {
        $this->property_id = $property_id;
    }

    public function submit()
    {
        $this->validate();
    
        $application = RentalApplication::create([
            'property_id' => $this->property_id,
            'tenant_id' => auth()->user()->id,
            'status' => 'pending',
            'employment_status' => $this->employment_status,
            'annual_income' => $this->annual_income,
            'ethereum_address' => $this->ethereum_address,
            'lease_start_date' => $this->lease_start_date,
            'lease_end_date' => $this->lease_end_date,
        ]);
    
        $application->initiateScreening();
    
        session()->flash('message', 'Application submitted and screening initiated!');
        return redirect()->route('tenant.applications');
    }

    public function render()
    {
        return view('livewire.rental-application-form', [
            'property' => Property::findOrFail($this->property_id),
        ]);
    }
}