<div>
    @section('content')
        <div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-blue-900">
            <!-- Header -->
            <div class="bg-black/30 backdrop-blur-sm border-b border-white/10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <div class="flex items-center">
                            <a href="{{ route('property.detail', $property->id) }}" 
                               class="text-white hover:text-gray-300 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                            </a>
                            <h1 class="ml-4 text-xl font-semibold text-white">
                                {{ $property->title }} - Holographic Tour
                            </h1>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="px-3 py-1 text-xs font-medium text-purple-300 bg-purple-900/50 rounded-full border border-purple-500/50">
                                {{ ucfirst($selectedDevice) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <!-- Holographic Viewer -->
                    <div class="lg:col-span-3">
                        <div class="bg-black/40 backdrop-blur-md rounded-2xl p-8 border border-white/10 shadow-2xl">
                            <!-- Viewer Canvas -->
                            <div class="relative bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl overflow-hidden" style="height: 600px;">
                                @if($tourMetadata)
                                    <!-- 3D Model Viewer -->
                                    <model-viewer 
                                        src="{{ $tourMetadata['model_url'] ?? '' }}"
                                        alt="Holographic view of {{ $property->title }}"
                                        ar
                                        ar-modes="webxr scene-viewer quick-look"
                                        camera-controls
                                        touch-action="pan-y"
                                        auto-rotate
                                        environment-image="neutral"
                                        shadow-intensity="1"
                                        exposure="1.2"
                                        class="w-full h-full"
                                        style="--poster-color: transparent;">
                                    </model-viewer>

                                    <!-- Holographic Effect Overlay -->
                                    <div class="absolute inset-0 pointer-events-none">
                                        <div class="absolute inset-0 bg-gradient-to-t from-purple-500/10 via-transparent to-blue-500/10 animate-pulse"></div>
                                        <div class="absolute inset-0 border-2 border-purple-500/30 rounded-xl"></div>
                                    </div>

                                    <!-- Viewing Angles Indicator -->
                                    <div class="absolute bottom-4 left-4 flex items-center space-x-2 bg-black/50 backdrop-blur-sm px-3 py-2 rounded-lg">
                                        <svg class="w-4 h-4 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-xs text-white font-medium">360° View Active</span>
                                    </div>
                                @else
                                    <div class="flex items-center justify-center h-full">
                                        <div class="text-center">
                                            <svg class="w-16 h-16 mx-auto text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-gray-400">No holographic data available</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Viewer Controls -->
                            <div class="mt-6 flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="changeViewerMode('interactive')" 
                                        class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $viewerMode === 'interactive' ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700' }}">
                                        Interactive
                                    </button>
                                    <button wire:click="changeViewerMode('presentation')" 
                                        class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $viewerMode === 'presentation' ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700' }}">
                                        Presentation
                                    </button>
                                    <button wire:click="changeViewerMode('fullscreen')" 
                                        class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $viewerMode === 'fullscreen' ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700' }}">
                                        Fullscreen
                                    </button>
                                </div>
                                <div class="text-xs text-gray-400">
                                    <kbd class="px-2 py-1 bg-gray-800 rounded">Click + Drag</kbd> to rotate • 
                                    <kbd class="px-2 py-1 bg-gray-800 rounded">Scroll</kbd> to zoom
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Property Info -->
                        <div class="bg-black/40 backdrop-blur-md rounded-xl p-6 border border-white/10">
                            <h3 class="text-lg font-semibold text-white mb-4">Property Details</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Price</span>
                                    <span class="text-white font-medium">{{ app(\App\Settings\GeneralSettings::class)->site_currency }} {{ number_format($property->price, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Bedrooms</span>
                                    <span class="text-white font-medium">{{ $property->bedrooms }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Bathrooms</span>
                                    <span class="text-white font-medium">{{ $property->bathrooms }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Area</span>
                                    <span class="text-white font-medium">{{ number_format($property->area_sqft) }} sq ft</span>
                                </div>
                            </div>
                        </div>

                        <!-- Display Devices -->
                        <div class="bg-black/40 backdrop-blur-md rounded-xl p-6 border border-white/10">
                            <h3 class="text-lg font-semibold text-white mb-4">Display Devices</h3>
                            <div class="space-y-2">
                                @foreach($supportedDevices as $key => $device)
                                    <button wire:click="selectDevice('{{ $key }}')" 
                                        class="w-full text-left px-4 py-3 rounded-lg transition-all {{ $selectedDevice === $key ? 'bg-purple-600 border-purple-400' : 'bg-gray-800/50 border-gray-700 hover:bg-gray-700' }} border">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <p class="text-sm font-medium {{ $selectedDevice === $key ? 'text-white' : 'text-gray-300' }}">
                                                    {{ $device['name'] }}
                                                </p>
                                                <p class="text-xs {{ $selectedDevice === $key ? 'text-purple-200' : 'text-gray-500' }} mt-1">
                                                    {{ $device['resolution'] }} • {{ $device['viewing_angle'] }}
                                                </p>
                                            </div>
                                            @if($selectedDevice === $key)
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Tour Info -->
                        <div class="bg-black/40 backdrop-blur-md rounded-xl p-6 border border-white/10">
                            <h3 class="text-lg font-semibold text-white mb-4">Tour Features</h3>
                            <ul class="space-y-3 text-sm text-gray-300">
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-purple-400 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    360° interactive rotation
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-purple-400 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    High-resolution 4K rendering
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-purple-400 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Realistic lighting & shadows
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-purple-400 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Augmented reality support
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-purple-400 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Multi-device compatibility
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.3.0/model-viewer.min.js"></script>
    @endpush
</div>
