<nav class="bg-green-900 fixed w-full z-10" x-data="{ isOpen: false, dropdownOpen: false }">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a class="navbar-brand flex items-center" href="/">
                <img src="{{ asset('/build/images/logo1.svg') }}" alt="Logo" class="h-8">
            </a>
            <div class="hidden lg:flex lg:items-center lg:space-x-4">
                <a href="/" class="btn-nav text-white hover:text-gray-300">Home</a>
                <a href="/contact" class="btn-nav text-white hover:text-gray-300">Contact</a>
                <a href="/about" class="btn-nav text-white hover:text-gray-300">About</a>
                @if(auth()->check())
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="btn-nav text-white hover:text-gray-300">
                            Welcome, {{ auth()->user()->name }}
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                            <a href="{{ route('filament.admin.tenant')}}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                        </div>
                    </div>
                @else
                    <a href="/login" class="btn-nav text-white hover:text-gray-300">Login</a>
                    <a href="/register" class="btn-nav text-white hover:text-gray-300">Register</a>
                @endif
            </div>
            <div class="lg:hidden">
                <button @click="isOpen = !isOpen" class="text-white focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div x-show="isOpen" class="lg:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="/" class="block px-3 py-2 text-white hover:bg-green-800">Home</a>
            <a href="/contact" class="block px-3 py-2 text-white hover:bg-green-800">Contact</a>
            <a href="/about" class="block px-3 py-2 text-white hover:bg-green-800">About</a>
            @if(auth()->check())
                <a href="{{ route('filament.admin.tenant')}}" class="block px-3 py-2 text-white hover:bg-green-800">Dashboard</a>
            @else
                <a href="/login" class="block px-3 py-2 text-white hover:bg-green-800">Login</a>
                <a href="/register" class="block px-3 py-2 text-white hover:bg-green-800">Register</a>
            @endif
        </div>
    </div>
</nav>
