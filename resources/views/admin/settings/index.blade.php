@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Settings</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="currency">Default Currency</label>
            <input type="text" name="currency" id="currency" class="form-control" value="{{ $settings ? $settings->currency : '' }}">
        </div>
        <!-- Ajoutez d'autres champs si nécessaire -->
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
