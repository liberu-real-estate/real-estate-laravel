@extends('layouts.app')

@section('title', 'Create Booking')

@section('content')
<div class="container">
    <h1>Create Booking</h1>
    <form action="{{ route('bookings.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" required>
            @error('date')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="time" class="form-label">Time</label>
            <input type="time" class="form-control" id="time" name="time" required>
            @error('time')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="staff_id" class="form-label">Staff Member</label>
            <select class="form-select" id="staff_id" name="staff_id" required>
                <option selected disabled>Choose...</option>
                @foreach($staffMembers as $staff)
                    <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                @endforeach
            </select>
            @error('staff_id')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes (Optional)</label>
            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
            @error('notes')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
