<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Property;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function index()
    {
        $auctions = Auction::with('property')->where('status', 'active')->get();
        return view('auctions.index', compact('auctions'));
    }

    public function show(Auction $auction)
    {
        return view('auctions.show', ['auction' => $auction]);
    }

    public function create(Property $property)
    {
        return view('auctions.create', compact('property'));
    }

    public function store(Request $request, Property $property)
    {
        $validatedData = $request->validate([
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'starting_price' => 'required|numeric|min:0',
            'minimum_increment' => 'required|numeric|min:1',
        ]);

        $auction = new Auction($validatedData);
        $auction->property_id = $property->id;
        $auction->status = 'pending';
        $auction->current_bid = $validatedData['starting_price'];
        $auction->save();

        return redirect()->route('auctions.show', $auction);
    }
}