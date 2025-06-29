<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ __('Complete Visit') }}
                </h2>
                <nav class="flex items-center space-x-2 text-sm mt-2">
                    <a href="{{ route('visits.index') }}" class="text-blue-500 hover:text-blue-700">
                        {{ __('All Visits') }}
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <a href="{{ route('visits.show', $visit) }}" class="text-blue-500 hover:text-blue-700">
                        {{ $visit->purpose }}
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-600">{{ __('Complete') }}</span>
                </nav>
            </div>
            <div>
                <a href="{{ route('visits.show', $visit) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-lg font-medium text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Back to Visit') }}
                </a>
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Complete Visit') }} | {{ config('app.name') }}
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl">
                <div class="p-6 sm:p-8">
                    <form method="POST" action="{{ route('visits.complete', $visit) }}">
                        @csrf

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Customer</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $visit->customer->name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Scheduled Date</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $visit->scheduled_date->format('M d, Y h:i A') }}</p>
                                </div>

                                @if($visit->expected_amount)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Expected Amount</label>
                                        <p class="mt-1 text-sm text-gray-900">৳{{ number_format($visit->expected_amount, 2) }}</p>
                                    </div>
                                @endif

                                <div>
                                    <label for="collected_amount" class="block text-sm font-medium text-gray-700 mb-1">Collected Amount *</label>
                                    <div class="relative rounded-lg shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">৳</span>
                                        </div>
                                        <input type="number" step="0.01" id="collected_amount" name="collected_amount" required
                                               class="block w-full pl-9 pr-12 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">Tk</span>
                                        </div>
                                    </div>
                                    @error('collected_amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="outcome" class="block text-sm font-medium text-gray-700 mb-1">Outcome *</label>
                                    <textarea id="outcome" name="outcome" rows="3" required
                                              class="block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                                    @error('outcome')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 border border-transparent rounded-lg font-medium text-sm text-white uppercase tracking-widest shadow-sm hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('Mark as Complete') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
