<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ __('Schedule New Visit') }}
                </h2>
                <nav class="flex items-center space-x-2 text-sm mt-2">
                    <a href="{{ route('visits.index') }}" class="text-blue-500 hover:text-blue-700">
                        {{ __('All Visits') }}
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-600">{{ __('New Visit') }}</span>
                </nav>
            </div>
            <div>
                <a href="{{ route('visits.collection-list') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-lg font-medium text-xs text-white uppercase tracking-widest shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a4 4 0 00-8 0v2H5v10h14V9h-2z" />
                    </svg>
                    {{ __('Collection List') }}
                </a>
                <a href="{{ route('visits.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-lg font-medium text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Back to Visits') }}
                </a>
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Create Schedule') }} | {{ config('app.name') }}
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl">
                <div class="p-6 sm:p-8">
                    <form method="POST" action="{{ route('visits.store') }}">
                        @csrf

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Customer Selection -->
                                <div>
                                    <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Customer') }} *</label>
                                    <select id="customer_id" name="customer_id" required
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg transition">
                                        <option value="">{{ __('Select Customer') }}</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }} (Due: ৳{{ number_format($customer->total_due, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Assigned To -->
                                <div>
                                    <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Assigned To') }} *</label>
                                    <select id="assigned_to" name="assigned_to" required
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg transition">
                                        @foreach($staffMembers as $staff)
                                            <option value="{{ $staff->id }}" {{ old('assigned_to') == $staff->id ? 'selected' : '' }}>
                                                {{ $staff->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('assigned_to')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Scheduled Date -->
                                <div>
                                    <label for="scheduled_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Scheduled Date') }} *</label>
                                    <input type="datetime-local" id="scheduled_date" name="scheduled_date" required
                                           value="{{ old('scheduled_date') }}"
                                           class="mt-1 block w-full pl-3 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('scheduled_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Expected Amount -->
                                <div>
                                    <label for="expected_amount" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Expected Amount') }}</label>
                                    <div class="relative rounded-lg shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">৳</span>
                                        </div>
                                        <input type="number" step="0.01" min="0" id="expected_amount" name="expected_amount"
                                               value="{{ old('expected_amount') }}"
                                               class="block w-full pl-9 pr-12 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">Tk</span>
                                        </div>
                                    </div>
                                    @error('expected_amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Purpose -->
                            <div>
                                <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Purpose') }} *</label>
                                <input type="text" id="purpose" name="purpose" required value="{{ old('purpose') }}"
                                       class="mt-1 block w-full pl-3 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('purpose')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Notes') }}</label>
                                <textarea id="notes" name="notes" rows="3"
                                          class="block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('notes') }}</textarea>
                                @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 border border-transparent rounded-lg font-medium text-sm text-white uppercase tracking-widest shadow-sm hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ __('Schedule Visit') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
