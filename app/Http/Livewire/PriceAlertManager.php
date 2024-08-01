<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\PriceAlert;

class PriceAlertManager extends Component
{
    public $propertyId;
    public $alertPercentage = 5;
    public $alertFrequency = 'daily';
    public $priceAlerts;

    protected $rules = [
        'alertPercentage' => 'required|numeric|min:0.1|max:100',
        'alertFrequency' => 'required|in:daily,weekly,monthly',
    ];

    public function mount($propertyId)
    {
        $this->propertyId = $propertyId;
        $this->loadPriceAlerts();
    }

    public function loadPriceAlerts()
    {
        $this->priceAlerts = auth()->user()->priceAlerts()->where('property_id', $this->propertyId)->get();
    }

    public function createAlert()
    {
        $this->validate();

        PriceAlert::create([
            'user_id' => auth()->id(),
            'property_id' => $this->propertyId,
            'initial_price' => $this->property->price,
            'alert_percentage' => $this->alertPercentage,
            'alert_frequency' => $this->alertFrequency,
            'is_active' => true,
        ]);

        $this->loadPriceAlerts();
        $this->reset(['alertPercentage', 'alertFrequency']);
        session()->flash('message', 'Price alert created successfully.');
    }

    public function toggleAlert($alertId)
    {
        $alert = PriceAlert::findOrFail($alertId);
        $alert->update(['is_active' => !$alert->is_active]);
        $this->loadPriceAlerts();
    }

    public function deleteAlert($alertId)
    {
        PriceAlert::findOrFail($alertId)->delete();
        $this->loadPriceAlerts();
        session()->flash('message', 'Price alert deleted successfully.');
    }

    public function render()
    {
        return view('livewire.price-alert-manager');
    }
}