
<nav x-data="{ isOpen: false }" class="bg-green-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex-shrink-0">
                    <img class="h-8 w-8" src="{{ asset('build/images/logo.png') }}" alt="{{ config('app.name') }} Logo">
                </a>
                <div class="hidden lg:block ml-10">
                    <div class="flex items-baseline space-x-4">
{!! app(App\Services\MenuService::class)->buildMenu() !!}
                    </div>
                </div>
            </div>
            <div class="hidden lg:block">
                @if(auth()->check())
                    <div class="ml-4 flex items-center md:ml-6">
                        <a href="{{ auth()->user()->hasRole('admin') ? '/admin' : '/dashboard' }}" class="text-white hover:bg-green-700 px-3 py-2 rounded-md text-sm font-medium">
                            {{ auth()->user()->hasRole('admin') ? 'Admin Dashboard' : 'Dashboard' }}
                        </a>
                    </div>
                @else
                    <div class="ml-4 flex items-center md:ml-6">
                        <a href="{{ route('login') }}" class="text-white hover:bg-green-700 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                        <a href="{{ route('register') }}" class="text-white hover:bg-green-700 px-3 py-2 rounded-md text-sm font-medium ml-2">Register</a>
                    </div>
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
            {!! app(App\Services\MenuService::class)->buildMenu()->addClass('flex flex-col space-y-2')->addItemClass('block px-3 py-2 rounded-md text-base font-medium text-white bg-green-700 hover:bg-green-600') !!}
            @if(auth()->check())
                <a href="{{ auth()->user()->hasRole('admin') ? '/admin' : '/dashboard' }}" class="block px-3 py-2 rounded-md text-base font-medium text-white bg-green-700 hover:bg-green-600">
                    {{ auth()->user()->hasRole('admin') ? 'Admin Dashboard' : 'Dashboard' }}
                </a>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white bg-green-700 hover:bg-green-600">Login</a>
                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white bg-green-700 hover:bg-green-600">Register</a>
            @endif
        </div>
    </div>
</nav>
