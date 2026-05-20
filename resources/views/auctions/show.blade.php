@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Auction for {{ $auction->property->title }}</h1>
        @livewire('auction-interface', ['auction' => $auction])
    </div>
@endsection