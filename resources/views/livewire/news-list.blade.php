@extends('layouts.app')
@section('content')
    <div class="">
        <div class="bg-white dark:bg-gray-900">
            <div class="grid max-w-(--breakpoint-xl) px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12">
                <div class="mr-auto place-self-center lg:col-span-7">
                    <h1
                        class="max-w-2xl mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-6xl dark:text-white">
                        Property News & Updates</h1>
                    <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">
                        Stay informed with the latest news, market insights, and updates from the real estate industry.
                        Discover trending topics, expert analysis, and important announcements to help you make informed
                        property decisions.
                    </p>
                </div>
                <div class="hidden lg:mt-0 lg:col-span-5 lg:flex">
                    <img src="https://static.vecteezy.com/system/resources/previews/011/999/262/original/3d-render-online-news-concept-smartphone-with-newspaper-and-coffee-cup-illustration-png.png"
                        alt="news">
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 py-8">
            <!-- Featured News Section -->
            @if($featuredNews->count() > 0 && !$search && !$featuredOnly)
                <div class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Featured Articles</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($featuredNews as $featured)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                                <div class="p-6">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold text-white bg-yellow-500 rounded-full mb-3">
                                        Featured
                                    </span>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                        <a href="{{ route('news.detail', $featured->slug) }}" class="hover:text-primary-600">
                                            {{ $featured->title }}
                                        </a>
                                    </h3>
                                    @if($featured->excerpt)
                                        <p class="text-gray-600 dark:text-gray-400 mb-4">{{ Str::limit($featured->excerpt, 120) }}</p>
                                    @endif
                                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                        <span>{{ $featured->published_at->format('M d, Y') }}</span>
                                        @if($featured->author)
                                            <span>By {{ $featured->author->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Search and Filter -->
            <div class="mb-8 flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search" id="search"
                            class="block w-full p-3 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Search news articles...">
                    </div>
                </div>
                <div>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="featuredOnly" class="sr-only peer">
                        <div class="relative w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Featured Only</span>
                    </label>
                </div>
            </div>

            <!-- News Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @forelse($news as $article)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="p-6">
                            @if($article->is_featured)
                                <span class="inline-block px-2 py-1 text-xs font-semibold text-white bg-yellow-500 rounded mb-2">
                                    Featured
                                </span>
                            @endif
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                <a href="{{ route('news.detail', $article->slug) }}" class="hover:text-primary-600">
                                    {{ $article->title }}
                                </a>
                            </h3>
                            @if($article->excerpt)
                                <p class="text-gray-600 dark:text-gray-400 mb-4">{{ Str::limit($article->excerpt, 150) }}</p>
                            @else
                                <p class="text-gray-600 dark:text-gray-400 mb-4">{{ Str::limit(strip_tags($article->content), 150) }}</p>
                            @endif
                            <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                <span>{{ $article->published_at->format('M d, Y') }}</span>
                                @if($article->author)
                                    <span>By {{ $article->author->name }}</span>
                                @endif
                            </div>
                            <a href="{{ route('news.detail', $article->slug) }}"
                                class="inline-block mt-4 text-primary-600 hover:text-primary-700 font-medium">
                                Read more →
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No news articles found</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            @if($search)
                                Try adjusting your search terms.
                            @else
                                Check back later for updates.
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $news->links() }}
            </div>
        </div>
    </div>
@endsection
