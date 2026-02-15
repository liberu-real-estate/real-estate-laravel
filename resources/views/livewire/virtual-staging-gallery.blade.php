<div class="virtual-staging-gallery">
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6">
        <h2 class="text-2xl font-bold mb-4">Virtual Staging Gallery</h2>
        <button wire:click="$set('showUploadModal', true)" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Upload New Image
        </button>
    </div>

    <!-- Image Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($images as $image)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="relative">
                    <img src="{{ asset('storage/' . $image['file_path']) }}" 
                         alt="{{ $image['file_name'] }}"
                         class="w-full h-64 object-cover">
                    
                    @if($image['is_staged'])
                        <span class="absolute top-2 right-2 bg-purple-600 text-white px-3 py-1 rounded-full text-sm">
                            Staged: {{ ucfirst($image['staging_style']) }}
                        </span>
                    @endif
                </div>

                <div class="p-4">
                    <p class="text-sm text-gray-600 mb-2">{{ $image['file_name'] }}</p>
                    
                    @if(!$image['is_staged'])
                        <div class="flex gap-2">
                            <button wire:click="stageExistingImage({{ $image['image_id'] }})"
                                    class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-sm flex-1">
                                Stage Image
                            </button>
                            <button wire:click="deleteImage({{ $image['image_id'] }})"
                                    wire:confirm="Are you sure you want to delete this image?"
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                Delete
                            </button>
                        </div>

                        @if(isset($image['staged_versions']) && count($image['staged_versions']) > 0)
                            <div class="mt-3">
                                <p class="text-xs text-gray-500 mb-2">Staged Versions:</p>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach($image['staged_versions'] as $staged)
                                        <div class="relative group">
                                            <img src="{{ asset('storage/' . $staged['file_path']) }}" 
                                                 alt="Staged"
                                                 class="w-full h-20 object-cover rounded">
                                            <span class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-70 text-white text-xs p-1 text-center">
                                                {{ ucfirst($staged['staging_style']) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="flex gap-2">
                            <button wire:click="deleteImage({{ $image['image_id'] }})"
                                    wire:confirm="Are you sure you want to delete this staged image?"
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm flex-1">
                                Delete Staged
                            </button>
                        </div>
                        @if($image['original_image_id'])
                            <p class="text-xs text-gray-500 mt-2">Original: Image #{{ $image['original_image_id'] }}</p>
                        @endif
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-gray-500">
                <p class="text-xl mb-2">No images yet</p>
                <p>Upload your first image to get started with virtual staging!</p>
            </div>
        @endforelse
    </div>

    <!-- Upload Modal -->
    @if($showUploadModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-xl font-bold mb-4">Upload Property Image</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Select Image
                    </label>
                    <input type="file" 
                           wire:model="uploadedImage" 
                           accept="image/jpeg,image/png,image/jpg"
                           class="w-full">
                    @error('uploadedImage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="autoStage" class="mr-2">
                        <span class="text-sm">Auto-stage image after upload</span>
                    </label>
                </div>

                @if($autoStage)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Staging Style
                        </label>
                        <select wire:model="selectedStagingStyle" class="w-full border rounded px-3 py-2">
                            <option value="">Select a style</option>
                            @foreach($this->stagingStyles as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('selectedStagingStyle') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                @endif

                <div class="flex gap-2">
                    <button wire:click="uploadImage"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex-1">
                        Upload
                    </button>
                    <button wire:click="$set('showUploadModal', false)"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Staging Modal -->
    @if($showStagingModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-xl font-bold mb-4">Virtual Staging</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Select Staging Style
                    </label>
                    <select wire:model="selectedStagingStyle" class="w-full border rounded px-3 py-2">
                        <option value="">Select a style</option>
                        @foreach($this->stagingStyles as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('selectedStagingStyle') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="bg-blue-50 border border-blue-200 p-3 rounded mb-4">
                    <p class="text-sm text-blue-800">
                        <strong>Note:</strong> This is a mock staging implementation. In production, 
                        this would use AI services to transform your image according to the selected style.
                    </p>
                </div>

                <div class="flex gap-2">
                    <button wire:click="applyStaging"
                            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded flex-1">
                        Apply Staging
                    </button>
                    <button wire:click="$set('showStagingModal', false)"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
