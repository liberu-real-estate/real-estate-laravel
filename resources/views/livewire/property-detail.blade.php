<div>
@section('content')
@section('meta')
    <title>{{ $property->meta_title }}</title>
    <meta name="description" content="{{ $property->meta_description }}">
    <meta name="keywords" content="real estate, property, {{ $property->title }}, {{ $property->location }}">
    <meta property="og:title" content="{{ $property->meta_title }}">
    <meta property="og:description" content="{{ $property->meta_description }}">
    <meta property="og:image" content="{{ $property->getFirstMediaUrl('images') }}">
    <meta property="og:url" content="{{ route('property.detail', ['propertyId' => $property->id, 'slug' => $property
