
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
