<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="bookAppointment" class="space-y-4">
        <div>
            <label for="selectedDate" class="block text-sm font-medium text-gray-700">Select a Date</label>
            <select id="selectedDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" wire:model="selectedDate">
                <option value="">Select a date</option>
                @foreach($availableDates as $date)
                    <option value="{{ $date }}">{{
