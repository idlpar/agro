<nav x-data="{ open: false }" class="bg-green-900 border-b border-green-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo Section -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="ml-3 font-bold text-white text-xl tracking-tight">Talukder Agro Eco Farm</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden space-x-6 sm:-my-px sm:ml-10 sm:flex">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:text-green-100 transition-colors duration-200">
                    {{ __('Dashboard') }}
                </x-nav-link>

                @if (auth()->user()?->isAdmin())
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" class="text-white hover:text-green-100 transition-colors duration-200">
                        {{ __('Staff') }}
                    </x-nav-link>
                @endif

                @can('viewAny', App\Models\User::class)
                    <x-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')" class="text-white hover:text-green-100 transition-colors duration-200">
                        {{ __('Customers') }}
                    </x-nav-link>
                @endcan

                @can('viewAny', App\Models\Product::class)
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="text-white hover:text-green-100 transition-colors duration-200">
                        {{ __('Products') }}
                    </x-nav-link>
                @endcan

                @can('viewAny', App\Models\Transaction::class)
                    <x-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')" class="text-white hover:text-green-100 transition-colors duration-200">
                        {{ __('Transactions') }}
                    </x-nav-link>
                @endcan

                @can('viewAny', App\Models\Payment::class)
                    <x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')" class="text-white hover:text-green-100 transition-colors duration-200">
                        {{ __('Payments') }}
                    </x-nav-link>
                @endcan
            </div>

            <!-- User Profile Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-white hover:text-green-100 focus:outline-none transition-colors duration-200">
                            <div class="flex items-center space-x-2">
                                <span class="font-medium">{{ Auth::user()->name }}</span>
                                <svg class="h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="p-2">
                            <div class="flex items-center space-x-2 mb-2">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a9 9 0 11-18 0 9 9 0 0118 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="font-medium text-gray-700">{{ Auth::user()->name }}</span>
                            </div>
                            <div class="flex items-center space-x-2 mb-4">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 14.917c1.664 1.664 4.345 1.664 6.008 0l7.997-7.997L13.997 16.883 2.003 8.884z" />
                                </svg>
                                <span class="text-sm text-gray-600">{{ Auth::user()->email }}</span>
                            </div>
                            <div class="border-t border-gray-800 pt-2">
                                <x-dropdown-link :href="route('profile.edit')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-900 transition-colors duration-200">
                                    {{ __('Profile') }}
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-900 transition-colors duration-200"
                                                     onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Menu Button -->
            <div class="sm:hidden flex items-center">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-green-100 hover:bg-green-800 focus:outline-none transition-colors duration-200">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
        <div class="space-y-1">
            <!-- Navigation Links -->
            <div class="px-2 pt-2 pb-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="block px-3 py-2 text-white hover:bg-green-600 transition-colors duration-200">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>

                @if (auth()->user()?->isAdmin())
                    <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" class="block px-3 py-2 text-white hover:bg-green-600 transition-colors duration-200">
                        {{ __('Staff') }}
                    </x-responsive-nav-link>
                @endif

                @can('viewAny', App\Models\User::class)
                    <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')" class="block px-3 py-2 text-white hover:bg-green-600 transition-colors duration-200">
                        {{ __('Customers') }}
                    </x-responsive-nav-link>
                @endcan

                @can('viewAny', App\Models\Product::class)
                    <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="block px-3 py-2 text-white hover:bg-green-600 transition-colors duration-200">
                        {{ __('Products') }}
                    </x-responsive-nav-link>
                @endcan

                @can('viewAny', App\Models\Transaction::class)
                    <x-responsive-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')" class="block px-3 py-2 text-white hover:bg-green-600 transition-colors duration-200">
                        {{ __('Transactions') }}
                    </x-responsive-nav-link>
                @endcan

                @can('viewAny', App\Models\Payment::class)
                    <x-responsive-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')" class="block px-3 py-2 text-white hover:bg-green-600 transition-colors duration-200">
                        {{ __('Payments') }}
                    </x-responsive-nav-link>
                @endcan
            </div>

            <!-- User Profile Section -->
            <div class="px-2 pt-4 border-t border-green-800">
                <div class="flex flex-col items-center space-y-2">
                    <div class="font-medium text-white">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-green-200">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-4 space-y-2">
                    <x-responsive-nav-link :href="route('profile.edit')" class="block px-3 py-2 text-white hover:bg-green-600 transition-colors duration-200">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" class="block px-3 py-2 text-white hover:bg-green-600 transition-colors duration-200"
                                               onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
