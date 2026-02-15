<div class="mt-6 bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
    <h4 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Share Your Neighborhood Experience</h4>
    <p class="text-gray-600 dark:text-gray-400 mb-4">Help others learn about this neighborhood. Your honest review makes a difference.</p>

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
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Review Title</label>
            <input wire:model="title" type="text" id="title" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Summarize your experience">
            @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="rating" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Overall Rating</label>
            <div class="flex items-center mt-1">
                @for ($i = 1; $i <= 5; $i++)
                    <button type="button" wire:click="$set('rating', {{ $i }})" class="text-3xl focus:outline-none transition-colors {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }} hover:scale-110">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                        </svg>
                    </button>
                @endfor
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">({{ $rating }} out of 5)</span>
            </div>
            @error('rating') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Your Review</label>
            <textarea wire:model="comment" id="comment" rows="4" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Share your experience living in or visiting this neighborhood"></textarea>
            @error('comment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500 dark:text-gray-400">Your review will be moderated before it appears publicly.</p>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
                Submit Review
            </button>
        </div>
    </form>
</div>
