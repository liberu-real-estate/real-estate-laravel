@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1 class="text-3xl font-bold mb-6">Our Real Estate Services</h1>
                
                <p class="mb-6">
                    At {{ \App\Helpers\SiteSettingsHelper::getSiteName() }}, we offer a comprehensive range of real estate services to meet all your property needs. Our experienced team of professionals is dedicated to providing exceptional service and expertise throughout your real estate journey.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h2 class="text-2xl font-semibold mb-4">For Buyers</h2>
                        <ul class="list-disc pl-5 space-y-2">
                            <li>Property search and matching</li>
                            <li>Viewing arrangements and accompaniment</li>
                            <li>Market analysis and property valuation</li>
                            <li>Negotiation support</li>
                            <li>Mortgage advice and financial planning</li>
                            <li>Legal and conveyancing assistance</li>
                        </ul>
                    </div>

                    <div>
                        <h2 class="text-2xl font-semibold mb-4">For Sellers</h2>
                        <ul class="list-disc pl-5 space-y-2">
                            <li>Free property valuation</li>
                            <li>Marketing and advertising strategies</li>
                            <li>Professional photography and virtual tours</li>
                            <li>Targeted buyer matching</li>
                            <li>Offer management and negotiation</li>
                            <li>Sale progression and completion support</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8">
                    <h2 class="text-2xl font-semibold mb-4">Additional Services</h2>
                    <ul class="list-disc pl-5 space-y-2">
                        <li>Property management for landlords</li>
                        <li>Tenant finding and screening</li>
                        <li>Investment property advice</li>
                        <li>Market research and analysis</li>
                        <li>Property refurbishment and renovation guidance</li>
                        <li>Auction services for buying and selling</li>
                    </ul>
                </div>

                <p class="mt-8">
                    Whether you're a first-time buyer, seasoned investor, or looking to sell your property, our team at {{ \App\Helpers\SiteSettingsHelper::getSiteName() }} is here to guide you every step of the way. Contact us today to learn more about how we can assist you with your real estate needs.
                </p>
            </div>
        </div>
    </div>
@endsection