<?php

namespace App\Http\Controllers;

use App\Models\RentalCharge;
use Illuminate\Http\Request;

class RentalChargeController extends Controller
{
    public function index()
    {
        $rentalCharges = RentalCharge::with(['property', 'tenant'])->paginate(15);
        return view('rental-charges.index', compact('rentalCharges'));
    }

    public function create()
    {
        return view('rental-charges.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'tenant_id' => 'required|exists:tenants,id',
            'amount' => 'required|numeric',
            'charge_date' => 'required|date',
            'description' => 'required|string',
            'status' => 'required|in:pending,paid,overdue',
        ]);

        RentalCharge::create($validatedData);

        return redirect()->route('rental-charges.index')->with('success', 'Rental charge created successfully.');
    }

    public function show(RentalCharge $rentalCharge)
    {
        return view('rental-charges.show', compact('rentalCharge'));
    }

    public function edit(RentalCharge $rentalCharge)
    {
        return view('rental-charges.edit', compact('rentalCharge'));
    }

    public function update(Request $request, RentalCharge $rentalCharge)
    {
        $validatedData = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'tenant_id' => 'required|exists:tenants,id',
            'amount' => 'required|numeric',
            'charge_date' => 'required|date',
            'description' => 'required|string',
            'status' => 'required|in:pending,paid,overdue',
        ]);

        $rentalCharge->update($validatedData);

        return redirect()->route('rental-charges.index')->with('success', 'Rental charge updated successfully.');
    }

    public function destroy(RentalCharge $rentalCharge)
    {
        $rentalCharge->delete();

        return redirect()->route('rental-charges.index')->with('success', 'Rental charge deleted successfully.');
    }
}