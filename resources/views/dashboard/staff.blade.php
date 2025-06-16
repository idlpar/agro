<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ auth()->user()->name . "'s Dashboard" }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('payments.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 rounded-lg text-white font-medium shadow-sm hover:from-green-600 hover:to-green-700 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Collect Payment
                </a>
                <a href="{{ route('visits.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white font-medium shadow-sm hover:from-blue-600 hover:to-blue-700 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Schedule Visit
                </a>
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Dashboard') }} | {{ config('app.name') }}
    </x-slot>


    <div class="py-6">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Customers Card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-50 text-blue-600 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">My Customers</p>
                                <p class="text-2xl font-bold mt-1">{{ $myCustomers }}</p>
                                <p class="text-sm text-gray-500">{{ $myActiveCustomers }} active</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                        <a href="{{ route('customers.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center justify-between">
                            <span>View all customers</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Revenue Card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-50 text-green-600 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Monthly Revenue</p>
                                <p class="text-2xl font-bold mt-1">৳{{ number_format($myMonthlyRevenue, 2) }}</p>
                                <p class="text-sm text-gray-500">৳{{ number_format($myOutstandingPayments, 2) }} outstanding</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                        <a href="{{ route('transactions.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center justify-between">
                            <span>View all transactions</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Transactions Card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-50 text-purple-600 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Recent Transactions</p>
                                <p class="text-2xl font-bold mt-1">{{ $myPerformance['transactions'] }}</p>
                                <p class="text-sm text-gray-500">last 30 days</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                        <a href="{{ route('transactions.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center justify-between">
                            <span>View all transactions</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Visits Card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-amber-50 text-amber-600 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Upcoming Visits</p>
                                <p class="text-2xl font-bold mt-1">{{ $myUpcomingVisits->count() }}</p>
                                <p class="text-sm text-gray-500">{{ $myPerformance['visits_completed'] }} completed</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                        <a href="{{ route('visits.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center justify-between">
                            <span>View all visits</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Transactions -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Transactions</h3>
                            <a href="{{ route('transactions.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">View All</a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($myRecentTransactions as $transaction)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $transaction->transaction_date->format('d M') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $transaction->customer->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $transaction->customer->phone }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $transaction->variant->name }} ({{ $transaction->variant->product->name }})
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            ৳{{ number_format($transaction->total_amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $transaction->is_paid ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                                {{ $transaction->is_paid ? 'Paid' : 'Due' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6">Quick Actions</h3>
                        <div class="space-y-4">
                            <a href="{{ route('transactions.create') }}" class="block p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-300 border border-green-100 flex items-center">
                                <div class="p-3 bg-green-100 rounded-lg mr-4 text-green-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Record New Sale</h4>
                                    <p class="text-sm text-gray-500">Create a new transaction</p>
                                </div>
                            </a>

                            <a href="{{ route('payments.create') }}" class="block p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-300 border border-blue-100 flex items-center">
                                <div class="p-3 bg-blue-100 rounded-lg mr-4 text-blue-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Record Payment</h4>
                                    <p class="text-sm text-gray-500">Accept customer payment</p>
                                </div>
                            </a>

                            <a href="{{ route('visits.create') }}" class="block p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors duration-300 border border-purple-100 flex items-center">
                                <div class="p-3 bg-purple-100 rounded-lg mr-4 text-purple-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Schedule Visit</h4>
                                    <p class="text-sm text-gray-500">Plan farm visit</p>
                                </div>
                            </a>

                            <!-- 👇 Visit List block -->
                            <a href="{{ route('payments.dueList') }}" class="block p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors duration-300 border border-yellow-100 flex items-center">
                                <div class="p-3 bg-yellow-100 rounded-lg mr-4 text-yellow-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Due Payments List</h4>
                                    <p class="text-sm text-gray-500">View scheduled and completed visits</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Second Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                <!-- Recent Payments -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Payments</h3>
                            <a href="{{ route('payments.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">View All</a>
                        </div>

                        <div class="space-y-4">
                            @foreach($myRecentPayments as $payment)
                                <div class="p-4 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors duration-300">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $payment->customer->name }}</h4>
                                            <div class="text-sm text-gray-500">{{ $payment->payment_date->format('d M Y') }}</div>
                                        </div>
                                        <div class="text-lg font-semibold text-green-600">
                                            ৳{{ number_format($payment->amount, 2) }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Top Products -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Top Products</h3>
                            <a href="{{ route('products.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">View All</a>
                        </div>

                        <div class="space-y-4">
                            @foreach($myTopProducts as $product)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $product->product->name }} - {{ $product->name }}</h4>
                                            <span class="text-xs font-medium bg-green-100 text-green-800 px-2 py-0.5 rounded-full">
                                            {{ $product->sold_quantity }} sold
                                        </span>
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500">
                                            ৳{{ number_format($product->default_price, 2) }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Upcoming Visits -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Upcoming Visits</h3>
                            <a href="{{ route('visits.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">View All</a>
                        </div>

                        <div class="space-y-4">
                            @forelse($myUpcomingVisits as $visit)
                                <div class="p-4 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors duration-300">
                                    <div class="flex justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $visit->purpose }}</h4>
                                            <div class="flex items-center mt-1 text-sm text-gray-500">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ $visit->customer->name }}
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-medium text-gray-500">
                                                {{ $visit->scheduled_date->format('d M') }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ $visit->scheduled_date->format('h:i A') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('visits.edit', $visit) }}" class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors">
                                            Details
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500">
                                    No upcoming visits scheduled
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Due List Section -->
            <div class="mt-6 bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Customers with Due Payments</h3>
                        <a href="{{ route('customers.index') }}?filter=due" class="text-sm font-medium text-blue-600 hover:text-blue-800">View All</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($customersWithDue as $customer)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold mr-3">
                                            {{ substr($customer->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $customer->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $customer->phone }}</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-medium bg-red-100 text-red-800 px-2 py-1 rounded-full">
                                    ৳{{ number_format($customer->total_due, 2) }} due
                                </span>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('customers.show', $customer) }}" class="flex-1 text-center py-2 bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition-colors">
                                        View
                                    </a>
                                    <a href="{{ route('payments.create', ['customer_id' => $customer->id]) }}" class="flex-1 text-center py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                        Collect
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Due Transactions Section -->
            <div class="mt-6 bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Due Transactions</h3>
                        <a href="{{ route('transactions.index') }}?status=due" class="text-sm font-medium text-blue-600 hover:text-blue-800">View All</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($myDueTransactions as $transaction)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $transaction->transaction_date->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $transaction->customer->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $transaction->customer->phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $transaction->variant->product->name }} - {{ $transaction->variant->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ৳{{ number_format($transaction->total_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                        ৳{{ number_format($transaction->paid_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                        ৳{{ number_format($transaction->due_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('payments.create', ['transaction_id' => $transaction->id]) }}" class="text-green-600 hover:text-green-800">
                                            Collect Payment
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
