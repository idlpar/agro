<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generate Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('reports.generate') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" name="start_date" id="start_date"
                                       value="{{ request('start_date') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" name="end_date" id="end_date"
                                       value="{{ request('end_date') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div class="flex items-end">
                                <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Generate
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Report content here -->
                    @if(request()->has('start_date'))
                        <div class="mt-8">
                            <h3 class="text-lg font-medium mb-4">Report from {{ $startDate }} to {{ $endDate }}</h3>

                            <!-- Transactions -->
                            <div class="mb-8">
                                <h4 class="font-medium mb-2">Transactions ({{ $transactions->count() }})</h4>
                                <!-- Display transactions table -->
                            </div>

                            <!-- Payments -->
                            <div class="mb-8">
                                <h4 class="font-medium mb-2">Payments ({{ $payments->count() }})</h4>
                                <!-- Display payments table -->
                            </div>

                            <!-- Customers -->
                            <div>
                                <h4 class="font-medium mb-2">New Customers ({{ $customers->count() }})</h4>
                                <!-- Display customers table -->
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
