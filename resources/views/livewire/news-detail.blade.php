@extends('layouts.app')
@section('content')
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-8">
        <div class="container mx-auto px-4 max-w-4xl">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('news.list') }}"
                    class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to News
                </a>
            </div>

            <!-- Article Content -->
            <article class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mb-8">
                <!-- Featured Badge -->
                @if($news->is_featured)
                    <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-yellow-500 rounded-full mb-4">
                        Featured Article
                    </span>
                @endif

                <!-- Title -->
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    {{ $news->title }}
                </h1>

                <!-- Meta Information -->
                <div class="flex items-center text-gray-600 dark:text-gray-400 text-sm mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mr-6">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $news->published_at->format('F d, Y') }}</span>
                    </div>
                    @if($news->author)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>By {{ $news->author->name }}</span>
                        </div>
                    @endif
                </div>

                <!-- Excerpt -->
                @if($news->excerpt)
                    <div class="text-xl text-gray-700 dark:text-gray-300 font-medium mb-6 italic">
                        {{ $news->excerpt }}
                    </div>
                @endif

                <!-- Content -->
                <div class="prose prose-lg dark:prose-invert max-w-none">
                    {!! $news->content !!}
                </div>

                <!-- Footer -->
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Last updated: {{ $news->updated_at->format('F d, Y') }}
                        </span>
                        <div class="flex gap-2">
                            <!-- Share buttons could go here -->
                        </div>
                    </div>
                </div>
            </article>

            <!-- Related News -->
            @if($relatedNews->count() > 0)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Related Articles</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedNews as $related)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                                        <a href="{{ route('news.detail', $related->slug) }}" class="hover:text-primary-600">
                                            {{ $related->title }}
                                        </a>
                                    </h3>
                                    @if($related->excerpt)
                                        <p class="text-gray-600 dark:text-gray-400 mb-3 text-sm">
                                            {{ Str::limit($related->excerpt, 100) }}
                                        </p>
                                    @endif
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $related->published_at->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
