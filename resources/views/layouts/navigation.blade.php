<nav x-data="{ open: false, mobileMenuOpen: false, dropdownOpen: null }" class="bg-gradient-to-r from-green-800 to-green-900 shadow-lg">
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <!-- Logo Section -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center group">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-white/10 group-hover:bg-white/20 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-300 group-hover:text-white transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span class="ml-3 font-bold text-white text-xl tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-green-200 to-white group-hover:from-white group-hover:to-green-200 transition-all duration-500">Talukder Agro Eco Farm</span>
                </a>
            </div>

            <!-- Desktop Navigation Links -->
            <div class="hidden lg:flex items-center space-x-1">
                <!-- Dashboard -->
                @php
                    $isDashboardActive = request()->routeIs('dashboard');
                @endphp

                <x-nav-link :href="route('dashboard')" :active="$isDashboardActive" class="relative px-4 py-2 text-green-100 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-300 group">
                    {{ __('Dashboard') }}
                    <span class="absolute bottom-0 left-1/2 w-0 h-0.5 bg-green-300 group-hover:w-4/5 group-hover:left-[10%] transition-all duration-300"></span>

                    @if ($isDashboardActive)
                        <span class="absolute bottom-0 left-[10%] w-4/5 h-0.5 bg-green-300"></span>
                    @endif
                </x-nav-link>


                <!-- People Dropdown -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button @click="open = !open" class="flex items-center px-4 py-2 text-green-100 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-300 group">
                        <span>People</span>
                        <svg class="ml-1 h-4 w-4 text-green-200 group-hover:text-white transition-transform duration-200" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute left-0 mt-2 w-56 origin-top-left rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-1">
                            @if (auth()->user()?->isAdmin())
                                <a href="{{ route('users.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition-colors duration-200 flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    {{ __('Staff Management') }}
                                </a>
                            @endif

                            @can('viewAny', App\Models\User::class)
                                <a href="{{ route('customers.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition-colors duration-200 flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    {{ __('Customers') }}
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <!-- Products -->
                @can('viewAny', App\Models\Product::class)
                    @php
                        $isProductActive = request()->routeIs('products.*');
                    @endphp

                    <x-nav-link :href="route('products.index')" :active="$isProductActive" class="relative px-4 py-2 text-green-100 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-300 group">
                        {{ __('Products') }}
                        <span class="absolute bottom-0 left-1/2 w-0 h-0.5 bg-green-300 group-hover:w-4/5 group-hover:left-[10%] transition-all duration-300"></span>

                        @if ($isProductActive)
                            <span class="absolute bottom-0 left-[10%] w-4/5 h-0.5 bg-green-300"></span>
                        @endif
                    </x-nav-link>
                @endcan


                <!-- Financial Dropdown -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button @click="open = !open" class="flex items-center px-4 py-2 text-green-100 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-300 group">
                        <span>Financial</span>
                        <svg class="ml-1 h-4 w-4 text-green-200 group-hover:text-white transition-transform duration-200" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute left-0 mt-2 w-56 origin-top-left rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-1">
                            @can('viewAny', App\Models\Transaction::class)
                                <a href="{{ route('transactions.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition-colors duration-200 flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                    </svg>
                                    {{ __('Transactions') }}
                                </a>
                            @endcan

                            @can('viewAny', App\Models\Payment::class)
                                <a href="{{ route('payments.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition-colors duration-200 flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    {{ __('Payments') }}
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Profile Dropdown -->
            <div class="hidden lg:flex lg:items-center lg:ml-6">
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                        <div class="relative">
                            <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center text-white font-semibold hover:bg-white/30 transition-all duration-300">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="absolute bottom-0 right-0 h-3 w-3 rounded-full bg-green-400 border-2 border-green-800"></div>
                        </div>
                        <span class="text-white font-medium">{{ Auth::user()->name }}</span>
                        <svg class="h-4 w-4 text-green-200 transition-transform duration-200" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute right-0 mt-2 w-64 origin-top-right rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-2 px-4">
                            <div class="flex items-center space-x-3 py-3 border-b border-gray-100">
                                <div class="h-10 w-10 rounded-full bg-green-600 flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                                </div>
                            </div>

                            <div class="py-2">
                                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 rounded-md transition-colors duration-200 flex items-center">
                                    <svg class="h-5 w-5 mr-2 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                    </svg>
                                    {{ __('Profile Settings') }}
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-3 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 rounded-md transition-colors duration-200 flex items-center">
                                        <svg class="h-5 w-5 mr-2 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                                        </svg>
                                        {{ __('Sign Out') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <div class="lg:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-green-100 hover:bg-white/10 focus:outline-none transition-colors duration-300">
                    <svg class="h-6 w-6" :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6" :class="{ 'hidden': !mobileMenuOpen, 'block': mobileMenuOpen }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="lg:hidden bg-green-800 shadow-xl rounded-b-lg">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <!-- Dashboard -->
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="block px-3 py-3 rounded-md text-base font-medium text-white hover:bg-white/10 transition-colors duration-300">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    {{ __('Dashboard') }}
                </div>
            </x-responsive-nav-link>

            <!-- People Accordion -->
            <div x-data="{ open: false }" class="rounded-md overflow-hidden">
                <button @click="open = !open" class="w-full flex justify-between items-center px-3 py-3 rounded-md text-base font-medium text-white hover:bg-white/10 transition-colors duration-300">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>People</span>
                    </div>
                    <svg class="h-4 w-4 transform transition-transform duration-200" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="open" x-collapse class="pl-11 space-y-1">
                    @if (auth()->user()?->isAdmin())
                        <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" class="block px-3 py-2 rounded-md text-base font-medium text-green-100 hover:bg-white/10 transition-colors duration-300">
                            {{ __('Staff Management') }}
                        </x-responsive-nav-link>
                    @endif

                    @can('viewAny', App\Models\User::class)
                        <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')" class="block px-3 py-2 rounded-md text-base font-medium text-green-100 hover:bg-white/10 transition-colors duration-300">
                            {{ __('Customers') }}
                        </x-responsive-nav-link>
                    @endcan
                </div>
            </div>

            <!-- Products -->
            @can('viewAny', App\Models\Product::class)
                <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="block px-3 py-3 rounded-md text-base font-medium text-white hover:bg-white/10 transition-colors duration-300">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        {{ __('Products') }}
                    </div>
                </x-responsive-nav-link>
            @endcan

            <!-- Financial Accordion -->
            <div x-data="{ open: false }" class="rounded-md overflow-hidden">
                <button @click="open = !open" class="w-full flex justify-between items-center px-3 py-3 rounded-md text-base font-medium text-white hover:bg-white/10 transition-colors duration-300">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Financial</span>
                    </div>
                    <svg class="h-4 w-4 transform transition-transform duration-200" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="open" x-collapse class="pl-11 space-y-1">
                    @can('viewAny', App\Models\Transaction::class)
                        <x-responsive-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')" class="block px-3 py-2 rounded-md text-base font-medium text-green-100 hover:bg-white/10 transition-colors duration-300">
                            {{ __('Transactions') }}
                        </x-responsive-nav-link>
                    @endcan

                    @can('viewAny', App\Models\Payment::class)
                        <x-responsive-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.*')" class="block px-3 py-2 rounded-md text-base font-medium text-green-100 hover:bg-white/10 transition-colors duration-300">
                            {{ __('Payments') }}
                        </x-responsive-nav-link>
                    @endcan
                </div>
            </div>

            <!-- User Profile Section -->
            <div class="pt-4 pb-3 border-t border-green-700">
                <div class="flex items-center px-4">
                    <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-white">{{ Auth::user()->name }}</div>
                        <div class="text-sm font-medium text-green-200">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')" class="block px-4 py-2 text-base font-medium text-white hover:bg-white/10 transition-colors duration-300">
                        {{ __('Profile Settings') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-base font-medium text-white hover:bg-white/10 transition-colors duration-300">
                            {{ __('Sign Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
