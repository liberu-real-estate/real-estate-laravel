
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
        <div class="px-2 pt-2 pb-3 space-y-2">
            <a href="/" class="block px-4 py-2 rounded-md bg-green-700 text-white hover:bg-green-600 transition duration-300 ease-in-out">Home</a>
            <a href="/contact" class="block px-4 py-2 rounded-md bg-green-700 text-white hover:bg-green-600 transition duration-300 ease-in-out">Contact</a>
            <a href="/about" class="block px-4 py-2 rounded-md bg-green-700 text-white hover:bg-green-600 transition duration-300 ease-in-out">About</a>
            <a href="/properties" class="block px-4 py-2 rounded-md bg-green-700 text-white hover:bg-green-600 transition duration-300 ease-in-out">Properties</a>
            @if(auth()->check())
                @if(auth()->user()->hasRole('staff'))
                    <a href="/staff" class="block px-4 py-2 rounded-md bg-green-700 text-white hover:bg-green-600 transition duration-300 ease-in-out">Staff Dashboard</a>
                @elseif(auth()->user()->hasRole('contractor'))
                    <a href="/contractor" class="block px-4 py-2 rounded-md bg-green-700 text-white hover:bg-green-600 transition duration-300 ease-in-out">Contractor Dashboard</a>
                @elseif(auth()->user()->hasRole('buyer'))
                    <a href="/buyer" class="block px-4 py-2 rounded-md bg-green-700 text-white hover:bg-green-600 transition duration-300 ease-in-out">Buyer Dashboard</a>
                @elseif(auth()->user()->hasRole('seller'))
                    <a href="/seller" class="block px-4 py-2 rounded-md bg-green-700 text-white hover:bg-green-600 transition duration-300 ease-in-out">Seller Dashboard</a>
                @elseif(auth()->user()->hasRole('tenant'))
                    <a href="/tenant" class="block px-4 py-2 rounded-md bg-green-700 text-white hover:bg-green-600 transition duration-300 ease-in-out">Tenant Dashboard</a>
                @elseif(auth()->user()->hasRole('landlord'))
                    <a href="/landlord" class="block px-4 py-2 rounded-md bg-green-700 text-white hover:bg-green-600 transition duration-300 ease-in-out">Landlord Dashboard</a>
                @elseif(auth()->user()->hasRole('admin'))
                    <a href="/admin" class="block px-4 py-2 rounded-md bg-green-700 text-white hover:bg-green-600 transition duration-300 ease-in-out">Admin Dashboard</a>
                @else
                    <a href="/dashboard" class="block px-4 py-2 rounded-md bg-green-700 text-white hover:bg-green-600 transition duration-300 ease-in-out">Dashboard</a>
                @endif
            @else
                <a href="/login" class="block px-4 py-2 rounded-md bg-green-700 text-white hover:bg-green-600 transition duration-300 ease-in-out">Login</a>
                <a href="/register" class="block px-4 py-2 rounded-md bg-green-700 text-white hover:bg-green-600 transition duration-300 ease-in-out">Register</a>
            @endif
        </div>
    </div>
</nav>
