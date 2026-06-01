<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\PostalCodeService;

class LocationAutocomplete extends Component
{
    public $query = '';
    public $results = [];

    protected $postalCodeService;

    public function boot(PostalCodeService $postalCodeService)
    {
        $this->postalCodeService = $postalCodeService;
    }

    public function updatedQuery()
    {
        $this->results = $this->postalCodeService->searchPostcodes($this->query);
    }

    public function selectLocation($location)
    {
        $this->query = $location['postcode'];
        $this->dispatch('locationSelected', $location);
        $this->results = [];
    }

    public function render()
    {
        return view('livewire.location-autocomplete');
    }
}