<div>
@section('content')
    @if(count($properties) > 0)
        @foreach ($properties as $property)
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold">{{ $property->title }}</h3>
                <p>{{ $property->location }}</p>
                <div class="text-sm text-gray-600 mt-2">
                    {{ $property->description }}
                </div>
            </div>
        @endforeach
    @else
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
            <p class="font-bold">Debug Information</p>
            <p>No properties found. Please check the logs for more information.</p>
        </div>
    @endif

    <div class="mt-4">
        {{ $properties->links() }}
    </div>

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
            <p class="font-bold">Error</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif
@endsection
</div>
