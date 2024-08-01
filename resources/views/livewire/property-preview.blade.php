<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            {{ $property->title }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            {{ $property->location }}
        </p>
    </div>
    <div class="border-t border-gray-200">
        <dl>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Price
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    ${{ number_format($property->price, 2) }}
                </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Description
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ $property->description }}
                </dd>
            </div>
            @if($property->custom_description)
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Custom Description
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $property->custom_description }}
                    </dd>
                </div>
            @endif
            <!-- Add more property details here -->
        </dl>
    </div>
    @if($property->getFirstMediaUrl('images'))
        <div class="px-4 py-5 sm:px-6">
            <h4 class="text-lg leading-6 font-medium text-gray-900">Images</h4>
            <div class="mt-2 grid grid-cols-2 gap-4">
                @foreach($property->getMedia('images') as $image)
                    <img src="{{ $image->getUrl() }}" alt="Property Image" class="w-full h-48 object-cover rounded-lg">
                @endforeach
            </div>
        </div>
    @endif
    @if($property->getFirstMediaUrl('videos'))
        <div class="px-4 py-5 sm:px-6">
            <h4 class="text-lg leading-6 font-medium text-gray-900">Video</h4>
            <div class="mt-2">
                <video controls class="w-full">
                    <source src="{{ $property->getFirstMediaUrl('videos') }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    @endif
</div>