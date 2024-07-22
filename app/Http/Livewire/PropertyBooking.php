    public function render()
    {
        return view('livewire.property-booking', [
            'availableDates' => $this->availableDates,
        ]);
    }
}