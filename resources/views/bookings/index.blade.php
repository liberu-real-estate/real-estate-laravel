@extends('layouts.app')

@section('title', 'Bookings List')

@section('content')
<div class="container">
    <h1>Bookings</h1>
    <a href="{{ route('bookings.create') }}" class="btn btn-primary mb-3">Create New Booking</a>
    <a href="{{ route('home') }}" class="btn btn-secondary mb-3">Back to Dashboard</a>
    @if($bookings->isEmpty())
        <p>No bookings available.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Staff Member</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $booking->date->format('Y-m-d') }}</td>
                        <td>{{ $booking->time }}</td>
                        <td>{{ $booking->staff->name }}</td>
                        <td>{{ $booking->notes }}</td>
                        <td>
                            <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
