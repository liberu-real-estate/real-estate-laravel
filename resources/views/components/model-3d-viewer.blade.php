@props(['modelUrl', 'propertyTitle' => 'Property'])

<div class="relative">
    @if($modelUrl)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                    </svg>
                    3D Model View
                </h3>
                <div class="flex items-center space-x-2">
                    <button onclick="resetModel3DCamera()" 
                        class="text-sm px-3 py-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white bg-gray-100 dark:bg-gray-700 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset View
                    </button>
                </div>
            </div>

            <!-- 3D Viewer Container -->
            <div id="model-3d-container" class="relative bg-gray-50 dark:bg-gray-900" style="height: 500px;">
                <!-- Loading state will be shown here -->
            </div>

            <!-- Controls Help -->
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                <div class="flex flex-wrap items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex items-center space-x-4">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                            </svg>
                            Left Click + Drag to Rotate
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                            </svg>
                            Scroll to Zoom
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11" />
                            </svg>
                            Right Click + Drag to Pan
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let viewer3D = null;

            document.addEventListener('DOMContentLoaded', function() {
                if (window.Model3DViewer && '{{ $modelUrl }}') {
                    viewer3D = new window.Model3DViewer('model-3d-container', '{{ $modelUrl }}');
                }
            });

            function resetModel3DCamera() {
                if (viewer3D) {
                    viewer3D.resetCamera();
                }
            }

            // Clean up on page unload
            window.addEventListener('beforeunload', function() {
                if (viewer3D) {
                    viewer3D.dispose();
                }
            });
        </script>
    @else
        <!-- No 3D Model Available -->
        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-8 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
            </svg>
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">3D Model Not Available</h3>
            <p class="text-gray-600 dark:text-gray-400">A 3D model for this property is not currently available.</p>
            <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Check back later or contact us for more information.</p>
        </div>
    @endif
</div>
