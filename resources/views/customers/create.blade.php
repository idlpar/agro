<x-app-layout>
    <x-slot name="title">
        {{ __('New Customer') }} | {{ config('app.name') }}
    </x-slot>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                    {{ __('Create New Customer') }}
                </h2>
                <nav class="flex items-center space-x-2 mt-1">
                    <a href="{{ route('users.index') }}" class="text-indigo-500 hover:text-indigo-700 transition-colors text-sm">
                        {{ __('Users') }}
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-600 text-sm font-medium">
                        {{ __('Create Customer') }}
                    </span>
                </nav>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow rounded-xl">
                <form method="POST" action="{{ route('customers.store') }}">
                    @csrf

                    <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-blue-50">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __('Customer Information') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ __('Fill in the details to create a new customer account') }}
                        </p>
                    </div>

                    <div class="px-6 py-6 space-y-6">
                        <input type="hidden" name="role" value="customer">

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Name -->
                            <div>
                                <x-label for="name" :value="__('Full Name')" class="block text-sm font-medium text-gray-700 mb-1" />
                                <x-input id="name" class="block w-full" type="text" name="name" :value="old('name')" required autofocus />
                                @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <x-label for="email" :value="__('Email Address')" class="block text-sm font-medium text-gray-700 mb-1" />
                                <x-input id="email" class="block w-full" type="email" name="email" :value="old('email')" />
                                @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <x-label for="phone" :value="__('Phone Number')" class="block text-sm font-medium text-gray-700 mb-1" />
                                <x-input id="phone" class="block w-full" type="tel" name="phone" :value="old('phone')" />
                                @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="sm:col-span-2">
                                <x-label for="address" :value="__('Address (Optional)')" class="block text-sm font-medium text-gray-700 mb-1" />
                                <textarea id="address" name="address" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address') }}</textarea>
                                @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                        <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-medium text-sm text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="ml-3 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-medium text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            {{ __('Create Customer') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
