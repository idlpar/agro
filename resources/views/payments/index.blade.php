<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    {{ __('Payments') }}
                </h2>
                <nav class="flex items-center space-x-2 text-sm mt-1">
                    <a href="{{ route('dashboard') }}" class="text-indigo-500 hover:text-indigo-700">
                        {{ __('Dashboard') }}
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-600">
                        {{ __('All Payments') }}
                    </span>
                </nav>
            </div>
            <div class="flex space-x-2">
                @can('create', App\Models\Payment::class)
                    <a href="{{ route('payments.create') }}"
                       class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        {{ __('New') }}
                    </a>
                @endcan
                <a href="{{ route('payments.dueList') }}"
                   class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ __('Due List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Payments') }} | {{ config('app.name') }}
    </x-slot>

    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 text-sm" role="alert">
                <div class="flex items-center">
                    <svg class="h-4 w-4 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 text-sm" role="alert">
                <div class="flex items-center">
                    <svg class="h-4 w-4 text-red-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        </div>
    @endif

    <div class="py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Combined Filters and Summary -->
            <div class="bg-white rounded-lg shadow mb-4">
                <div class="p-3 border-b border-gray-200">
                    <form method="GET" action="{{ route('payments.index') }}" class="space-y-3">
                        <!-- First Row - Main Filters -->
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-2">
                            <!-- Customer -->
                            <div>
                                <label for="customer_id" class="block text-xs font-medium text-gray-700 mb-1">{{ __('Customer') }}</label>
                                <select id="customer_id" name="customer_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs h-8">
                                    <option value="">{{ __('All') }}</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Receiver -->
                            <div>
                                <label for="receiver_id" class="block text-xs font-medium text-gray-700 mb-1">{{ __('Received By') }}</label>
                                <select id="receiver_id" name="receiver_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs h-8">
                                    <option value="">{{ __('All') }}</option>
                                    @foreach($receivers as $receiver)
                                        <option value="{{ $receiver->id }}" {{ request('receiver_id') == $receiver->id ? 'selected' : '' }}>
                                            {{ $receiver->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-xs font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                                <select id="status" name="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs h-8">
                                    <option value="">{{ __('All') }}</option>
                                    <option value="allocated" {{ request('status') === 'allocated' ? 'selected' : '' }}>{{ __('Allocated') }}</option>
                                    <option value="unallocated" {{ request('status') === 'unallocated' ? 'selected' : '' }}>{{ __('Unallocated') }}</option>
                                </select>
                            </div>

                            <!-- Date Range -->
                            <div>
                                <label for="date_range" class="block text-xs font-medium text-gray-700 mb-1">{{ __('Date') }}</label>
                                <select id="date_range" name="date_range" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs h-8">
                                    <option value="">{{ __('All Time') }}</option>
                                    <option value="today" {{ request('date_range') === 'today' ? 'selected' : '' }}>{{ __('Today') }}</option>
                                    <option value="this_week" {{ request('date_range') === 'this_week' ? 'selected' : '' }}>{{ __('This Week') }}</option>
                                    <option value="this_month" {{ request('date_range') === 'this_month' ? 'selected' : '' }}>{{ __('This Month') }}</option>
                                    <option value="custom" {{ request('date_range') === 'custom' ? 'selected' : '' }}>{{ __('Custom') }}</option>
                                </select>
                            </div>

                            <!-- Search -->
                            <div>
                                <label for="search" class="block text-xs font-medium text-gray-700 mb-1">{{ __('Search') }}</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <svg class="h-3 w-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                                           class="block w-full pl-7 pr-2 py-1.5 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-xs h-8"
                                           placeholder="{{ __('Search...') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Custom Date Range (hidden by default) -->
                        <div id="custom_date_range" class="grid grid-cols-1 md:grid-cols-3 gap-2 {{ request('date_range') === 'custom' ? '' : 'hidden' }}">
                            <div>
                                <label for="start_date" class="block text-xs font-medium text-gray-700 mb-1">{{ __('From') }}</label>
                                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs h-8">
                            </div>
                            <div>
                                <label for="end_date" class="block text-xs font-medium text-gray-700 mb-1">{{ __('To') }}</label>
                                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs h-8">
                            </div>
                        </div>

                        <!-- Amount Range -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <div>
                                <label for="amount_min" class="block text-xs font-medium text-gray-700 mb-1">{{ __('Min Amount') }}</label>
                                <input type="number" step="0.01" min="0" name="amount_min" id="amount_min" value="{{ request('amount_min') }}"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs h-8"
                                       placeholder="{{ __('Min') }}">
                            </div>
                            <div>
                                <label for="amount_max" class="block text-xs font-medium text-gray-700 mb-1">{{ __('Max Amount') }}</label>
                                <input type="number" step="0.01" min="0" name="amount_max" id="amount_max" value="{{ request('amount_max') }}"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs h-8"
                                       placeholder="{{ __('Max') }}">
                            </div>
                        </div>

                        <!-- Second Row - Buttons and Summary -->
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-2">
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 h-8">
                                    {{ __('Filter') }}
                                </button>
                                <a href="{{ route('payments.index') }}"
                                   class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 h-8">
                                    {{ __('Reset') }}
                                </a>
                                <button type="button" id="exportBtn"
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 h-8">
                                    {{ __('Export') }}
                                </button>
                            </div>

                            <!-- Summary Stats -->
                            <div class="flex items-center space-x-4 text-2xs">
                                <div class="flex items-center">
                                    <span class="text-gray-500 mr-1">{{ __('Total:') }}</span>
                                    <span class="font-medium">{{ $payments->total() }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-gray-500 mr-1">{{ __('Amount:') }}</span>
                                    <span class="font-medium">{{ number_format($totalAmount, 2) }} Tk</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-gray-500 mr-1">{{ __('Allocated:') }}</span>
                                    <span class="font-medium text-green-600">{{ number_format($totalAllocated, 2) }} Tk</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-gray-500 mr-1">{{ __('Unallocated:') }}</span>
                                    <span class="font-medium text-amber-600">{{ number_format($totalUnallocated, 2) }} Tk</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payments Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'payment_date', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center">
                                    {{ __('Date') }}
                                    @if(request('sort') === 'payment_date')
                                        @if(request('direction') === 'asc')
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Customer') }}
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'amount', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center">
                                    {{ __('Amount') }}
                                    @if(request('sort') === 'amount')
                                        @if(request('direction') === 'asc')
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Allocated') }}
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Received By') }}
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Status') }}
                            </th>
                            <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($payments as $payment)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="text-xs text-gray-900">{{ $payment->payment_date->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $payment->payment_date->format('h:i A') }}</div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-6 w-6 rounded-full bg-indigo-100 flex items-center justify-center text-xs text-indigo-600">
                                            {{ strtoupper(substr($payment->customer->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-2">
                                            <div class="text-2xs font-medium text-gray-900 truncate max-w-[120px]">{{ $payment->customer->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-xs font-medium text-gray-900">
                                    {{ number_format($payment->amount, 2) }} Tk
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-500">
                                    {{ number_format($payment->transactions->sum('total_amount'), 2) }} Tk
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center text-xs text-blue-600">
                                            {{ strtoupper(substr($payment->receiver->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-2">
                                            <div class="text-2xs font-medium text-gray-900 truncate max-w-[120px]">{{ $payment->receiver->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-2xs leading-4 font-semibold rounded-full {{ $payment->transactions->count() ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $payment->transactions->count() ? 'Allocated' : 'Unallocated' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-right text-lg font-medium">
                                    <div class="flex items-center justify-end space-x-1">
                                        <a href="{{ route('payments.show', $payment->id) }}"
                                           class="text-indigo-600 hover:text-indigo-900 p-1" title="{{ __('View') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        @can('update', $payment)
                                            <a href="{{ route('payments.edit', $payment->id) }}"
                                               class="text-blue-600 hover:text-blue-900 p-1" title="{{ __('Edit') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                        @endcan

                                        @can('delete', $payment)
                                            <form action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 p-1" title="{{ __('Delete') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-4 text-center text-xs text-gray-500">
                                    {{ __('No payments found') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if($payments->hasPages())
                    <div class="px-4 py-3 border-t border-gray-200">
                        {{ $payments->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Toggle custom date range
                const dateRangeSelect = document.getElementById('date_range');
                const customDateRange = document.getElementById('custom_date_range');

                dateRangeSelect.addEventListener('change', function() {
                    if (this.value === 'custom') {
                        customDateRange.classList.remove('hidden');
                    } else {
                        customDateRange.classList.add('hidden');
                    }
                });

                // Export functionality
                document.getElementById('exportBtn').addEventListener('click', function() {
                    const url = new URL(window.location.href);
                    url.searchParams.set('export', 'true');
                    window.location.href = url.toString();
                });

                // Delete confirmation
                const deleteForms = document.querySelectorAll('.delete-form');

                deleteForms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'Cancel',
                            focusCancel: true,
                            reverseButtons: true,
                            customClass: {
                                confirmButton: 'bg-red-600 hover:bg-red-700 text-white text-md px-3 py-1 rounded',
                                cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-800 text-md px-3 py-1 rounded mr-2'
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
