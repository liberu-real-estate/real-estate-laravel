<div>
    <h2 class="text-2xl font-semibold mb-4">Agent Performance Dashboard</h2>

    <div class="mb-4">
        <label for="startDate" class="mr-2">Start Date:</label>
        <input type="date" wire:model="startDate" id="startDate" class="border rounded px-2 py-1">
        <label for="endDate" class="ml-4 mr-2">End Date:</label>
        <input type="date" wire:model="endDate" id="endDate" class="border rounded px-2 py-1">
        <button wire:click="updateMetrics" class="ml-4 bg-blue-500 text-white px-4 py-2 rounded">Update</button>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-8">
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold mb-2">Listings Added</h3>
            <p class="text-3xl font-bold">{{ $metrics['listings_added'] }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold mb-2">Properties Sold</h3>
            <p class="text-3xl font-bold">{{ $metrics['properties_sold'] }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold mb-2">Appointments Scheduled</h3>
            <p class="text-3xl font-bold">{{ $metrics['appointments_scheduled'] }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold mb-2">Average Rating</h3>
            <p class="text-3xl font-bold">{{ number_format($metrics['average_rating'], 1) }}</p>
        </div>
    </div>

    <h3 class="text-xl font-semibold mb-4">Recent Client Feedback</h3>
    <div class="bg-white p-4 rounded shadow">
        @forelse($feedback as $review)
            <div class="mb-4 pb-4 border-b last:border-b-0">
                <p class="font-semibold">{{ $review->user->name }}</p>
                <p class="text-sm text-gray-600">Rating: {{ $review->rating }}/5</p>
                <p class="mt-2">{{ $review->comment }}</p>
            </div>
        @empty
            <p>No feedback available.</p>
        @endforelse
    </div>
</div>