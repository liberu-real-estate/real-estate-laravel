<nav class="bg-green-900 fixed w-full z-10" x-data="{ isOpen: false }">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a class="navbar-brand flex items-center" href="/">
                <img src="{{ asset('/build/images/logo.png') }}" alt="Logo" class="h-8">
            </a>
            <div class="hidden lg:flex lg:items-center lg:space-x-4">
                {{ app(App\Services\MenuService::class)->buildMenu() }}
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
            {{ app(App\Services\MenuService::class)->buildMenu() }}
        </div>
    </div>
</nav>