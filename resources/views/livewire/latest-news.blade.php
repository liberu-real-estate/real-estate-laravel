<div class="py-12 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Latest News & Updates</h2>
            <a href="{{ route('news.list') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                View All →
            </a>
        </div>

        @if($news->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($news as $article)
                    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="p-6">
                            @if($article->is_featured)
                                <span class="inline-block px-2 py-1 text-xs font-semibold text-white bg-yellow-500 rounded mb-3">
                                    Featured
                                </span>
                            @endif
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                <a href="{{ route('news.detail', $article->slug) }}" class="hover:text-primary-600">
                                    {{ $article->title }}
                                </a>
                            </h3>
                            @if($article->excerpt)
                                <p class="text-gray-600 dark:text-gray-400 mb-4">{{ Str::limit($article->excerpt, 120) }}</p>
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
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500 dark:text-gray-400">No news articles available at the moment.</p>
            </div>
        @endif
    </div>
</div>
