
            <p>Total properties: {{ $properties->total() }}</p>
            <p>Current page: {{ $properties->currentPage() }}</p>
            <p>Last page: {{ $properties->lastPage() }}</p>
            <p>Properties on this page: {{ $properties->count() }}</p>
        </div>
    @endif
@endsection
</div>
