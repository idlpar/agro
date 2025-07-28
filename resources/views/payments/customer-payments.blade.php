<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    {{ __('Receivables') }}
                </h2>
                <nav class="flex items-center space-x-2 text-sm mt-1">
                    <a href="{{ route('dashboard') }}" class="text-indigo-500 hover:text-indigo-700">
                        {{ __('Dashboard') }}
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-600">
                        {{ __('Due List') }}
                    </span>
                </nav>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('payments.index') }}"
                   class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                    {{ __('Back to Payments') }}
                </a>
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Receivables') }} | {{ config('app.name') }}
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white rounded-lg shadow mb-4">
                <div class="p-3 border-b border-gray-200">
                    <form method="GET" action="{{ route('payments.dueList') }}" class="space-y-3">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
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
                                           placeholder="{{ __('Search customers...') }}">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 h-8">
                                    {{ __('Filter') }}
                                </button>
                                <a href="{{ route('payments.dueList') }}"
                                   class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 h-8">
                                    {{ __('Reset') }}
                                </a>
                            </div>

                            <div class="text-2xs text-gray-500">
                                {{ __('Total Due:') }} <span class="font-bold text-red-600">{{ number_format($customers->sum('total_due'), 2) }} Tk</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Due Customers Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Customer') }}
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Due Amount') }}
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Last Payment') }}
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Due Since') }}
                            </th>
                            <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($customers as $customer)
                            <tr class="{{ $customer->total_due > 0 ? 'bg-red-50' : '' }}">
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-sm font-medium text-indigo-600">
                                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-xs font-medium text-gray-900">{{ $customer->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $customer->phone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-xs font-bold text-red-600">
                                    {{ number_format($customer->total_due, 2) }} Tk
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-500">
                                    @if($customer->payments()->exists())
                                        {{ $customer->payments()->latest()->first()->payment_date->format('d M Y') }}
                                        <div class="text-xs text-gray-400">
                                            {{ number_format($customer->payments()->latest()->first()->amount, 2) }} Tk
                                        </div>
                                    @else
                                        {{ __('Never') }}
                                    @endif
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-500">
                                    @if($customer->transactions()->unpaid()->exists())
                                        {{ $customer->transactions()->unpaid()->oldest()->first()->transaction_date->format('d M Y') }}
                                        <div class="text-xs text-gray-400">
                                            {{ $customer->transactions()->unpaid()->count() }} {{ Str::plural('transaction', $customer->transactions()->unpaid()->count()) }}
                                        </div>
                                    @else
                                        {{ __('N/A') }}
                                    @endif
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-right text-lg font-medium">
                                    <div class="flex items-center justify-end space-x-1">
                                        <a href="{{ route('payments.customerPayments', $customer->id) }}"
                                           class="text-indigo-600 hover:text-indigo-900 p-1" title="{{ __('View Payments') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        @can('create', App\Models\Payment::class)
                                            <a href="{{ route('payments.create', ['customer_id' => $customer->id]) }}"
                                               class="text-green-600 hover:text-green-900 p-1" title="{{ __('Record Payment') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-xs text-gray-500">
                                    {{ __('No customers with due amounts found') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if($customers->hasPages())
                    <div class="px-4 py-3 border-t border-gray-200">
                        {{ $customers->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
