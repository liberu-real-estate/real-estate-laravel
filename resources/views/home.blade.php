@extends('layouts.app')

@section('content')
@component('components.header')
@endcomponent

@component('components.navigation')
@endcomponent

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h2 class="text-center">Featured Properties</h2>
            <div class="row">
                @foreach($featuredProperties as $property)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="{{ $property->image }}" class="card-img-top" alt="{{ $property->title }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $property->title }}</h5>
                                <p class="card-text">{{ Str::limit($property->description, 100) }}</p>
                                <a href="{{ url('/properties/'.$property->id) }}" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="text-center">About Our Business</h2>
            <p class="text-center">Liberu Real Estate is revolutionizing the real estate industry with innovative tools and seamless workflows. Our platform empowers real estate professionals, property owners, and investors.</p>
        </div>
    </div>
</div>

@component('components.footer')
@endcomponent
@endsection
