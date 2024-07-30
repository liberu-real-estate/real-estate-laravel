<?php

namespace App\Http\Livewire;

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
        $this->emitUp('locationSelected', $location);
        $this->results = [];
    }

    public function render()
    {
        return view('livewire.location-autocomplete');
    }
}