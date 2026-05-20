<div class="vr-design-studio">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">VR Property Design Studio</h2>
            <p class="text-gray-600">{{ $property->title }}</p>
        </div>
        <button wire:click="createNewDesign" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            New Design
        </button>
    </div>

    <!-- Flash Messages -->
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

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Designs List Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-lg font-semibold mb-4">Your Designs</h3>
                
                @if (count($designs) > 0)
                    <div class="space-y-2">
                        @foreach ($designs as $design)
                            <div 
                                wire:click="selectDesign({{ $design['id'] }})"
                                class="p-3 rounded cursor-pointer transition {{ $selectedDesignId == $design['id'] ? 'bg-blue-100 border border-blue-500' : 'bg-gray-50 hover:bg-gray-100' }}"
                            >
                                <div class="font-medium text-sm">{{ $design['name'] }}</div>
                                <div class="text-xs text-gray-500">{{ $design['style'] ?? 'No style' }}</div>
                                <div class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($design['created_at'])->diffForHumans() }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No designs yet. Create your first VR design!</p>
                @endif
            </div>
        </div>

        <!-- Design Canvas -->
        <div class="lg:col-span-3">
            @if ($selectedDesign)
                <div class="bg-white rounded-lg shadow">
                    <!-- Design Header -->
                    <div class="border-b p-4 flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-semibold">{{ $selectedDesign->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $selectedDesign->description }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <button wire:click="editDesign" class="text-blue-600 hover:text-blue-800 px-3 py-1 rounded border border-blue-600">
                                Edit
                            </button>
                            <button wire:click="cloneDesign" class="text-green-600 hover:text-green-800 px-3 py-1 rounded border border-green-600">
                                Clone
                            </button>
                            <button wire:click="deleteDesign({{ $selectedDesign->id }})" 
                                    wire:confirm="Are you sure you want to delete this design?"
                                    class="text-red-600 hover:text-red-800 px-3 py-1 rounded border border-red-600">
                                Delete
                            </button>
                        </div>
                    </div>

                    <!-- VR Canvas Area -->
                    <div class="p-6">
                        <div class="bg-gray-100 rounded-lg h-96 flex items-center justify-center mb-4">
                            <div class="text-center">
                                <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path>
                                </svg>
                                <p class="text-gray-600">3D VR Canvas</p>
                                <p class="text-sm text-gray-500">VR rendering will appear here</p>
                                <p class="text-xs text-gray-400 mt-2">Provider: {{ $selectedDesign->vr_provider }}</p>
                            </div>
                        </div>

                        <!-- Design Controls -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <button wire:click="openFurnitureModal" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                                Add Furniture
                            </button>
                            <button wire:click="openThumbnailModal" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                                Upload Thumbnail
                            </button>
                            <button class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                                View in VR
                            </button>
                        </div>

                        <!-- Furniture List -->
                        @if ($selectedDesign->furniture_items && count($selectedDesign->furniture_items) > 0)
                            <div class="mt-6">
                                <h4 class="font-semibold mb-3">Furniture Items</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($selectedDesign->furniture_items as $item)
                                        <div class="bg-gray-50 p-3 rounded flex justify-between items-center">
                                            <div>
                                                <div class="font-medium text-sm">{{ $item['type'] }}</div>
                                                <div class="text-xs text-gray-500">{{ $item['category'] }}</div>
                                                <div class="text-xs text-gray-400">Position: {{ implode(', ', $item['position']) }}</div>
                                            </div>
                                            <button wire:click="removeFurniture('{{ $item['id'] }}')" 
                                                    class="text-red-600 hover:text-red-800">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Design Info -->
                        <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold mb-2">Design Information</h4>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <span class="text-gray-600">Style:</span>
                                    <span class="ml-2 font-medium">{{ $selectedDesign->style ?? 'None' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Visibility:</span>
                                    <span class="ml-2 font-medium">{{ $selectedDesign->is_public ? 'Public' : 'Private' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Views:</span>
                                    <span class="ml-2 font-medium">{{ $selectedDesign->view_count }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Created:</span>
                                    <span class="ml-2 font-medium">{{ $selectedDesign->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <svg class="w-32 h-32 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No Design Selected</h3>
                    <p class="text-gray-600 mb-6">Select a design from the sidebar or create a new one to get started</p>
                    <button wire:click="createNewDesign" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
                        Create Your First Design
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Design Create/Edit Modal -->
    @if ($showDesignModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <h3 class="text-lg font-semibold mb-4">{{ $selectedDesignId ? 'Edit Design' : 'Create New Design' }}</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" wire:model="designName" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="My Design">
                        @error('designName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea wire:model="designDescription" class="w-full border border-gray-300 rounded px-3 py-2" rows="3" placeholder="Describe your design..."></textarea>
                        @error('designDescription') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Style</label>
                        <select wire:model="designStyle" class="w-full border border-gray-300 rounded px-3 py-2">
                            <option value="">Select a style...</option>
                            @foreach ($this->designStyles as $key => $style)
                                <option value="{{ $key }}">{{ $style['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" wire:model="isPublic" id="isPublic" class="mr-2">
                        <label for="isPublic" class="text-sm text-gray-700">Make this design public</label>
                    </div>
                </div>

                <div class="flex justify-end space-x-2 mt-6">
                    <button wire:click="showDesignModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button wire:click="{{ $selectedDesignId ? 'updateDesign' : 'saveDesign' }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        {{ $selectedDesignId ? 'Update' : 'Create' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Furniture Add Modal -->
    @if ($showFurnitureModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <h3 class="text-lg font-semibold mb-4">Add Furniture</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select wire:model="furnitureCategory" class="w-full border border-gray-300 rounded px-3 py-2">
                            <option value="">Select category...</option>
                            @foreach ($this->furnitureCategories as $category => $items)
                                <option value="{{ $category }}">{{ ucfirst(str_replace('_', ' ', $category)) }}</option>
                            @endforeach
                        </select>
                        @error('furnitureCategory') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select wire:model="furnitureType" class="w-full border border-gray-300 rounded px-3 py-2" {{ !$furnitureCategory ? 'disabled' : '' }}>
                            <option value="">Select type...</option>
                            @if ($furnitureCategory && isset($this->furnitureCategories[$furnitureCategory]))
                                @foreach ($this->furnitureCategories[$furnitureCategory] as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('furnitureType') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                        <div class="grid grid-cols-3 gap-2">
                            <input type="number" wire:model="furniturePositionX" placeholder="X" class="border border-gray-300 rounded px-2 py-1 text-sm">
                            <input type="number" wire:model="furniturePositionY" placeholder="Y" class="border border-gray-300 rounded px-2 py-1 text-sm">
                            <input type="number" wire:model="furniturePositionZ" placeholder="Z" class="border border-gray-300 rounded px-2 py-1 text-sm">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-2 mt-6">
                    <button wire:click="showFurnitureModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button wire:click="addFurniture" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Add Furniture
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Thumbnail Upload Modal -->
    @if ($showThumbnailModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <h3 class="text-lg font-semibold mb-4">Upload Thumbnail</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Choose Image</label>
                        <input type="file" wire:model="thumbnailUpload" accept="image/*" class="w-full border border-gray-300 rounded px-3 py-2">
                        @error('thumbnailUpload') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    @if ($thumbnailUpload)
                        <div class="border rounded p-2">
                            <img src="{{ $thumbnailUpload->temporaryUrl() }}" class="w-full h-auto rounded">
                        </div>
                    @endif
                </div>

                <div class="flex justify-end space-x-2 mt-6">
                    <button wire:click="showThumbnailModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button wire:click="uploadThumbnail" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                        Upload
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
