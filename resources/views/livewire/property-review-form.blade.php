<div class="mt-8 bg-white shadow-lg rounded-lg p-6">
    <h3 class="text-2xl font-bold mb-4">Share Your Experience</h3>
    <p class="text-gray-600 mb-4">Your honest review helps others make informed decisions. Please share your experience with this property.</p>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @error('general')
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ $message }}</span>
        </div>
    @enderror

    <form wire:submit.prevent="submitReview">
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Review Title</label>
            <input wire:model="title" type="text" id="title" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Summarize your experience">
            @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="rating" class="block text-sm font-medium text-gray-700">Overall Rating</label>
            <div class="flex items-center mt-1">
                @for ($i = 1; $i <= 5; $i++)
                    <button type="button" wire:click="$set('rating', {{ $i }})" class="text-2xl focus:outline-none {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }}">★</button>
                @endfor
            </div>
            @error('rating') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="comment" class="block text-sm font-medium text-gray-700">Your Review</label>
            <textarea wire:model="comment" id="comment" rows="4" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Tell others about your experience with this property"></textarea>
            @error('comment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">Your review will be moderated before it appears publicly.</p>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Submit Review
            </button>
        </div>
    </form>
</div>