@extends('layouts.app')

@section('content')
<div class="property-details">
    <!-- Property details go here -->

    <div id="booking-calendar-app">
        <booking-calendar :property-id="{{ $property->id }}"></booking-calendar>
    </div>
</div>

@push('scripts')
<script src="{{ mix('js/app.js') }}"></script>
<script>
    new Vue({
        el: '#booking-calendar-app',
        components: {
            'booking-calendar': BookingCalendar
        }
    });
</script>
@endpush
@endsection
