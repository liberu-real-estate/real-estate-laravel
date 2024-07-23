<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Property;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TenancyApplicationForm extends Component
{
    public $property;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ];

    public function mount(Property $property)
    {
        $this->property = $property;
    }

    public function submitApplication()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $tenantRole = Role::findByName('tenant');
        $user->assignRole($tenantRole);

        // Here you would typically create a tenancy application record
        // associated with the user and property

        session()->flash('message', 'Tenancy application submitted successfully.');
        return redirect()->route('properties.show', $this->property);
    }

    public function render()
    {
        return view('livewire.tenancy-application-form');
    }
}