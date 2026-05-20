<?php

namespace App\Http\Controllers;

use App\Models\PriceAlert;
use App\Models\Property;
use Illuminate\Http\Request;

class PriceAlertController extends Controller
{
    public function store(Request $request, Property $property)
    {
        $validatedData = $request->validate([
            'alert_percentage' => 'required|numeric|min:0.1|max:100',
            'alert_frequency' => 'required|in:daily,weekly,monthly',
        ]);

        $priceAlert = new PriceAlert([
            'user_id' => auth()->id(),
            'property_id' => $property->id,
            'initial_price' => $property->price,
            'alert_percentage' => $validatedData['alert_percentage'],
            'alert_frequency' => $validatedData['alert_frequency'],
            'is_active' => true,
        ]);

        $priceAlert->save();

        return redirect()->back()->with('success', 'Price alert created successfully.');
    }

    public function update(Request $request, PriceAlert $priceAlert)
    {
        $validatedData = $request->validate([
            'alert_percentage' => 'required|numeric|min:0.1|max:100',
            'alert_frequency' => 'required|in:daily,weekly,monthly',
            'is_active' => 'boolean',
        ]);

        $priceAlert->update($validatedData);

        return redirect()->back()->with('success', 'Price alert updated successfully.');
    }

    public function destroy(PriceAlert $priceAlert)
    {
        $priceAlert->delete();

        return redirect()->back()->with('success', 'Price alert deleted successfully.');
    }
}