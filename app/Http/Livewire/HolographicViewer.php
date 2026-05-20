<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Services\HolographicTourService;

class HolographicViewer extends Component
{
    public $property;
    public $tourMetadata;
    public $selectedDevice = 'web_viewer';
    public $supportedDevices = [];
    public $viewerMode = 'interactive'; // interactive, presentation, fullscreen

    protected $holographicService;

    public function boot(HolographicTourService $holographicService)
    {
        $this->holographicService = $holographicService;
    }

    public function mount($propertyId)
    {
        $this->property = Property::findOrFail($propertyId);
        $this->tourMetadata = $this->holographicService->getMetadata($this->property);
        $this->supportedDevices = $this->holographicService->getSupportedDevices();
    }

    public function selectDevice($device)
    {
        $this->selectedDevice = $device;
        $this->emit('deviceChanged', $device);
    }

    public function changeViewerMode($mode)
    {
        $this->viewerMode = $mode;
        $this->emit('viewerModeChanged', $mode);
    }

    public function render()
    {
        return view('livewire.holographic-viewer')->layout('layouts.app');
    }
}
