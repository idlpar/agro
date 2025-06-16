<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ __('Visit Details') }}
                </h2>
                <nav class="flex items-center space-x-2 text-sm mt-2">
                    <a href="{{ route('visits.index') }}" class="text-blue-500 hover:text-blue-700">
                        {{ __('All Visits') }}
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-600">{{ $visit->purpose }}</span>
                </nav>
            </div>
            <div>
                @if(!$visit->completed_at && $visit->scheduled_date > now())
                    <form method="POST" action="{{ route('visits.complete', $visit) }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-lg font-medium text-xs text-white uppercase tracking-widest shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ __('Mark Complete') }}
                        </button>
                    </form>
                @endif
                <a href="{{ route('visits.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-lg font-medium text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('Back to Visits') }}
                </a>
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Show Schedule') }} | {{ config('app.name') }}
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl">
                <!-- Visit Details -->
                <div class="p-6 sm:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Visit Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500">Customer</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $visit->customer->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Purpose</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $visit->purpose }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Status</p>
                                    <p class="text-sm font-medium text-gray-900">
                                        @if($visit->completed_at)
                                            <span class="text-green-600">Completed</span>
                                        @elseif($visit->scheduled_date < now())
                                            <span class="text-red-600">Missed</span>
                                        @else
                                            <span class="text-blue-600">Upcoming</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Schedule Details</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500">Scheduled Date</p>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $visit->scheduled_date->format('l, F j, Y') }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $visit->scheduled_date->format('g:i A') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Assigned To</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $visit->assignedTo->name }}</p>
                                </div>
                                @if($visit->expected_amount)
                                    <div>
                                        <p class="text-sm text-gray-500">Expected Amount</p>
                                        <p class="text-sm font-medium text-gray-900">
                                            ৳{{ number_format($visit->expected_amount, 2) }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($visit->notes)
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Notes</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $visit->notes }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Completion Details -->
                    @if($visit->completed_at)
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Completion Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-500">Completed At</p>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $visit->completed_at->format('l, F j, Y g:i A') }}
                                    </p>
                                </div>
                                @if($visit->collected_amount)
                                    <div>
                                        <p class="text-sm text-gray-500">Collected Amount</p>
                                        <p class="text-sm font-medium text-gray-900">
                                            ৳{{ number_format($visit->collected_amount, 2) }}
                                        </p>
                                    </div>
                                @endif
                                @if($visit->outcome)
                                    <div class="md:col-span-2">
                                        <p class="text-sm text-gray-500">Outcome</p>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $visit->outcome }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Customer's Due Transactions -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Customer's Due Transactions</h3>
                        @if($visit->customer->transactions->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($visit->customer->transactions as $transaction)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $transaction->transaction_date->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $transaction->product->name }}
                                                @if($transaction->variant)
                                                    <span class="text-xs text-gray-500">({{ $transaction->variant->name }})</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $transaction->quantity }} {{ $transaction->product->unit }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ৳{{ number_format($transaction->total_amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($transaction->is_paid)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            Paid
                                                        </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">
                                                            Due
                                                        </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No due transactions found for this customer.</p>
                        @endif
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 text-right">
                    @can('update', $visit)
                        <a href="{{ route('visits.edit', $visit) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-lg font-medium text-xs text-white uppercase tracking-widest shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ __('Edit Visit') }}
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
