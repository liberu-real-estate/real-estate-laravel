<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class PropertyDetail extends Component
{
    public $property;
    public $neighborhood;
    public $availableDates = [];

    public function mount($propertyId)
    {
        $this->property = Property::with(['neighborhood', 'category', 'images'])->findOrFail($propertyId);
        $this->neighborhood = $this->property->neighborhood;
        $this->availableDates = $this->property->getAvailableDates();
    }

    public function createTenancyApplication()
    {
        // Create a new user with 'tenant' role
        $user = User::create([
            'name' => 'New Tenant',
            'email' => 'newtenant' . time() . '@example.com',
            'password' => Hash::make('temporary_password'),
        ]);

        $tenantRole = Role::findByName('tenant');
        $user->assignRole($tenantRole);

        // Redirect to the rental application form
        return redirect()->route('rental.apply', ['property' => $this->property->id, 'user' => $user->id]);
    }

    public function render()
    {
        return view('livewire.property-detail')->layout('layouts.app');
    }
}