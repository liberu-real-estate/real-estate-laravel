@extends('layouts.app')

@section('title', 'Edit Booking')

@section('content')
<div class="container">
    <h1>Edit Booking</h1>
    <form action="{{ route('bookings.update', $booking->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" value="{{ $booking->date }}" required>
        </div>
        <div class="mb-3">
            <label for="time" class="form-label">Time</label>
            <input type="time" class="form-control" id="time" name="time" value="{{ $booking->time }}" required>
        </div>
        <div class="mb-3">
            <label for="staff_id" class="form-label">Staff Member</label>
            <select class="form-select" id="staff_id" name="staff_id" required>
                @foreach($staffMembers as $staff)
                    <option value="{{ $staff->id }}" @if($staff->id == $booking->staff_id) selected @endif>{{ $staff->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control" id="notes" name="notes" rows="3">{{ $booking->notes }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
