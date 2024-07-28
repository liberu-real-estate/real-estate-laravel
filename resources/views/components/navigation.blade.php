<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ url('/') }}">
        <img src="{{ asset('images/logo.png') }}" alt="Liberu Real Estate Logo" height="40">
        {{ config('app.name') }}
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav ml-auto">
            <a class="nav-item nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
            <a class="nav-item nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ url('/about') }}">About Us</a>
            <a class="nav-item nav-link {{ request()->is('properties/featured') ? 'active' : '' }}" href="{{ url('/properties/featured') }}">Featured Properties</a>
            <a class="nav-item nav-link {{ request()->is('contact') ? 'active' : '' }}" href="{{ url('/contact') }}">Contact</a>
        </div>
    </div>
</nav>
