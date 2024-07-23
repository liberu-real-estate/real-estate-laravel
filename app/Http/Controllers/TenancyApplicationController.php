<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TenancyApplicationController extends Controller
{
    public function create(Property $property)
    {
        return view('tenancy.apply', compact('property'));
    }

    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $tenantRole = Role::findByName('tenant');
        $user->assignRole($tenantRole);

        // Here you would typically create a tenancy application record
        // associated with the user and property

        return redirect()->route('properties.show', $property)->with('success', 'Tenancy application submitted successfully.');
    }
}