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

    protected $rules = [
        'property_id' => 'required|exists:properties,id',
        'employment_status' => 'required|string',
        'annual_income' => 'required|numeric|min:0',
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
        ]);

        session()->flash('message', 'Application submitted successfully!');
        return redirect()->route('tenant.applications');
    }

    public function render()
    {
        return view('livewire.rental-application-form', [
            'property' => Property::findOrFail($this->property_id),
        ]);
    }
}