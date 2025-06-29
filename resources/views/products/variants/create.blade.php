<x-app-layout>
    <x-slot name="title">
        {{ __('Create Variant') }} | {{ config('app.name') }}
    </x-slot>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                    {{ __('Add Variant to') }}: {{ $product->name }}
                </h2>
                <nav class="flex items-center space-x-2 mt-1">
                    <a href="{{ route('products.index') }}" class="text-indigo-500 hover:text-indigo-700 transition-colors text-sm">
                        {{ __('Products') }}
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <a href="{{ route('products.show', $product->id) }}" class="text-indigo-500 hover:text-indigo-700 transition-colors text-sm">
                        {{ $product->name }}
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-600 text-sm font-medium">
                        {{ __('New Variant') }}
                    </span>
                </nav>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-xl overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-green-50 to-teal-50 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Variant Information') }}</h3>
                </div>
                <div class="px-6 py-6">
                    <form method="POST" action="{{ route('variants.store', $product->id) }}">
                        @csrf

                        <div class="space-y-6">
                            <div>
                                <x-label for="name" :value="__('Variant Name')" class="block text-sm font-medium text-gray-700 mb-1" />
                                <x-input id="name" class="block w-full" type="text" name="name" required autofocus />
                                @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-label for="default_price" :value="__('Default Price (Tk)')" class="block text-sm font-medium text-gray-700 mb-1" />
                                <x-input id="default_price" class="block w-full" type="number" step="0.01" min="0" name="default_price" required />
                                @error('default_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 space-x-3">
                            <a href="{{ route('products.show', $product->id) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-gray-600 hover:to-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-green-600 hover:to-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                                {{ __('Add Variant') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
