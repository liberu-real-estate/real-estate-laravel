<div class="agent-recommendations">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-900">Recommended Agents</h3>
        @if(empty($searchContext))
            <button wire:click="generateMatches" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Find My Matches
            </button>
        @endif
    </div>

    @if(count($recommendations) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recommendations as $agent)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <img src="{{ $agent->profile_photo_url }}" alt="{{ $agent->name }}" class="w-16 h-16 rounded-full object-cover">
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">{{ $agent->name }}</h4>
                                <p class="text-sm text-gray-600">Real Estate Agent</p>
                            </div>
                        </div>

                        @if(isset($agent->match_score))
                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium text-gray-700">Match Score</span>
                                    <span class="text-sm font-bold text-blue-600">{{ round($agent->match_score) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $agent->match_score }}%"></div>
                                </div>
                            </div>
                        @endif

                        @if(isset($agent->match_details['match_reasons']) && count($agent->match_details['match_reasons']) > 0)
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Why this agent?</p>
                                <ul class="space-y-1">
                                    @foreach(array_slice($agent->match_details['match_reasons'], 0, 2) as $reason)
                                        <li class="flex items-start text-xs text-gray-600">
                                            <svg class="w-4 h-4 text-green-500 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $reason }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-2 mb-4 text-sm">
                            <div class="text-center p-2 bg-gray-50 rounded">
                                <p class="text-gray-500 text-xs">Properties</p>
                                <p class="font-semibold text-gray-900">{{ $agent->properties->count() }}</p>
                            </div>
                            <div class="text-center p-2 bg-gray-50 rounded">
                                <p class="text-gray-500 text-xs">Rating</p>
                                <p class="font-semibold text-gray-900">{{ number_format($agent->averageRating() ?? 0, 1) }} ⭐</p>
                            </div>
                        </div>

                        <div class="flex space-x-2">
                            <a href="mailto:{{ $agent->email }}" class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                Contact
                            </a>
                            <a href="{{ route('agent.profile', $agent) }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                View Profile
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12 bg-gray-50 rounded-lg">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No agent recommendations yet</h3>
            <p class="mt-1 text-sm text-gray-500">We'll help you find the perfect agent for your needs.</p>
            <div class="mt-6">
                <button wire:click="generateMatches" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
                    Find My Perfect Agent
                </button>
            </div>
        </div>
    @endif
</div>
