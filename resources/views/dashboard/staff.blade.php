<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ auth()->user()->name }}'s Dashboard
                </h2>
                <p class="text-sm text-gray-500 mt-1">Your performance overview</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('payments.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 rounded-lg text-white font-medium shadow-md hover:from-green-600 hover:to-green-700 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Collect Payment
                </a>
                <a href="{{ route('visits.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white font-medium shadow-md hover:from-blue-600 hover:to-blue-700 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Schedule Visit
                </a>
                <a href="{{ route('reports.generate') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg text-white font-medium shadow-md hover:from-indigo-600 hover:to-indigo-700 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Generate Report
                </a>
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Your Dashboard') }} | {{ config('app.name') }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Customers Card -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-sm overflow-hidden border border-blue-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-800">My Customers</p>
                                <p class="text-2xl font-bold mt-1 text-gray-900">{{ $myCustomers }}</p>
                                <div class="flex items-center mt-2">
                                    <span class="text-sm text-blue-600">
                                        {{ $myActiveCustomers }} active
                                    </span>
                                </div>
                            </div>
                            <div class="p-3 rounded-full bg-white text-blue-600 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white/50 px-6 py-3 border-t border-blue-100/50">
                        <a href="{{ route('customers.index') }}" class="text-sm font-medium text-blue-700 hover:text-blue-900 flex items-center justify-between">
                            <span>View all customers</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Revenue Card -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-sm overflow-hidden border border-green-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-800">Monthly Revenue</p>
                                <p class="text-2xl font-bold mt-1 text-gray-900">৳{{ number_format($myMonthlyRevenue, 2) }}</p>
                                <div class="flex items-center mt-2">
                                    <span class="text-sm text-green-600">
                                        ৳{{ number_format($myOutstandingPayments, 2) }} outstanding
                                    </span>
                                </div>
                            </div>
                            <div class="p-3 rounded-full bg-white text-green-600 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white/50 px-6 py-3 border-t border-green-100/50">
                        <a href="{{ route('transactions.index') }}" class="text-sm font-medium text-green-700 hover:text-green-900 flex items-center justify-between">
                            <span>View all transactions</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Performance Card -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-sm overflow-hidden border border-purple-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-purple-800">My Performance</p>
                                <p class="text-2xl font-bold mt-1 text-gray-900">{{ $myPerformance['transactions'] }}</p>
                                <div class="flex items-center mt-2">
                                    <span class="text-sm text-purple-600">
                                        {{ $myPerformance['visits_completed'] }} visits
                                    </span>
                                    <span class="text-xs text-gray-500 ml-2">last 30 days</span>
                                </div>
                            </div>
                            <div class="p-3 rounded-full bg-white text-purple-600 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white/50 px-6 py-3 border-t border-purple-100/50">
                        <a href="{{ route('transactions.index') }}" class="text-sm font-medium text-purple-700 hover:text-purple-900 flex items-center justify-between">
                            <span>View performance</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Visits Card -->
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl shadow-sm overflow-hidden border border-amber-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-amber-800">Upcoming Visits</p>
                                <p class="text-2xl font-bold mt-1 text-gray-900">{{ $myUpcomingVisits->count() }}</p>
                                <div class="flex items-center mt-2">
                                    <span class="text-sm text-amber-600">
                                        {{ $myPerformance['visits_completed'] }} completed
                                    </span>
                                </div>
                            </div>
                            <div class="p-3 rounded-full bg-white text-amber-600 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white/50 px-6 py-3 border-t border-amber-100/50">
                        <a href="{{ route('visits.index') }}" class="text-sm font-medium text-amber-700 hover:text-amber-900 flex items-center justify-between">
                            <span>View all visits</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Revenue Chart -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Revenue</h3>
                        <div class="h-64">
                            <canvas id="myRevenueChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Customer Growth Chart -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Growth</h3>
                        <div class="h-64">
                            <canvas id="myCustomerGrowthChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Product Performance Chart -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Products (Last 30 Days)</h3>
                        <div class="h-64">
                            <canvas id="myProductPerformanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Transactions -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
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
                                            {{ $transaction->transaction_date->format('M d') }}
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
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
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

                            <a href="{{ route('payments.dueList') }}" class="block p-4 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors duration-300 border border-amber-100 flex items-center">
                                <div class="p-3 bg-amber-100 rounded-lg mr-4 text-amber-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Due Payments List</h4>
                                    <p class="text-sm text-gray-500">View pending payments</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                <!-- Recent Payments -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Payments</h3>
                            <a href="{{ route('payments.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">View All</a>
                        </div>

                        <div class="space-y-4">
                            @foreach($myRecentPayments as $payment)
                                <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-300">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $payment->customer->name }}</h4>
                                            <div class="text-sm text-gray-500">{{ $payment->payment_date->format('M d, Y') }}</div>
                                        </div>
                                        <div class="text-lg font-semibold text-green-600">
                                            ৳{{ number_format($payment->amount, 2) }}
                                        </div>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-400">
                                        Collected by {{ $payment->receiver->name }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Top Products -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Top Products</h3>
                            <a href="{{ route('products.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">View All</a>
                        </div>

                        <div class="space-y-4">
                            @foreach($myTopProducts as $product)
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
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
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Upcoming Visits</h3>
                            <a href="{{ route('visits.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">View All</a>
                        </div>

                        <div class="space-y-4">
                            @forelse($myUpcomingVisits as $visit)
                                <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-300">
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
                                                {{ $visit->scheduled_date->format('M d') }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ $visit->scheduled_date->format('h:i A') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('visits.edit', $visit) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
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
            <div class="mt-6 bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
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
            <div class="mt-6 bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
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
                                        {{ $transaction->transaction_date->format('M d, Y') }}
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

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Color palette for charts
            const chartColors = {
                blue: {
                    bg: 'rgba(59, 130, 246, 0.1)',
                    border: 'rgba(59, 130, 246, 1)',
                    hover: 'rgba(59, 130, 246, 0.8)'
                },
                green: {
                    bg: 'rgba(16, 185, 129, 0.1)',
                    border: 'rgba(16, 185, 129, 1)',
                    hover: 'rgba(16, 185, 129, 0.8)'
                },
                purple: {
                    bg: 'rgba(139, 92, 246, 0.1)',
                    border: 'rgba(139, 92, 246, 1)',
                    hover: 'rgba(139, 92, 246, 0.8)'
                }
            };

            // Revenue Chart
            const revenueCtx = document.getElementById('myRevenueChart').getContext('2d');
            const revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: @json($myRevenueChartData['labels'] ?? []),
                    datasets: [{
                        label: 'Monthly Revenue',
                        data: @json($myRevenueChartData['data'] ?? []),
                        backgroundColor: chartColors.blue.bg,
                        borderColor: chartColors.blue.border,
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: chartColors.blue.border,
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            titleFont: { family: 'Inter', size: 13, weight: '600' },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 10,
                            cornerRadius: 6,
                            boxPadding: 6,
                            callbacks: {
                                label: function(context) {
                                    return `৳${context.raw.toLocaleString()}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: { family: 'Inter', size: 11, weight: '500' },
                                color: '#6b7280',
                                callback: function(value) {
                                    return `৳${value.toLocaleString()}`;
                                },
                                padding: 8
                            },
                            grid: {
                                color: 'rgba(209, 213, 219, 0.1)',
                                drawBorder: false,
                                drawTicks: false
                            }
                        },
                        x: {
                            ticks: {
                                font: { family: 'Inter', size: 11, weight: '500' },
                                color: '#6b7280',
                                maxRotation: 0,
                                minRotation: 0,
                                padding: 8
                            },
                            grid: { display: false }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutCubic'
                    }
                }
            });

            // Customer Growth Chart
            const customerGrowthCtx = document.getElementById('myCustomerGrowthChart').getContext('2d');
            const customerGrowthChart = new Chart(customerGrowthCtx, {
                type: 'bar',
                data: {
                    labels: @json($myCustomerGrowthChartData['labels'] ?? []),
                    datasets: [{
                        label: 'New Customers',
                        data: @json($myCustomerGrowthChartData['data'] ?? []),
                        backgroundColor: chartColors.green.bg,
                        borderColor: chartColors.green.border,
                        borderWidth: 1,
                        borderRadius: 4,
                        hoverBackgroundColor: chartColors.green.hover
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            titleFont: { family: 'Inter', size: 13, weight: '600' },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 10,
                            cornerRadius: 6,
                            boxPadding: 6,
                            callbacks: {
                                label: function(context) {
                                    return `${context.raw} new customers`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: { family: 'Inter', size: 11, weight: '500' },
                                color: '#6b7280',
                                precision: 0,
                                padding: 8
                            },
                            grid: {
                                color: 'rgba(209, 213, 219, 0.1)',
                                drawBorder: false,
                                drawTicks: false
                            }
                        },
                        x: {
                            ticks: {
                                font: { family: 'Inter', size: 11, weight: '500' },
                                color: '#6b7280',
                                maxRotation: 0,
                                minRotation: 0,
                                padding: 8
                            },
                            grid: { display: false }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutCubic'
                    }
                }
            });

            // Product Performance Chart
            const productPerformanceCtx = document.getElementById('myProductPerformanceChart').getContext('2d');
            const productPerformanceChart = new Chart(productPerformanceCtx, {
                type: 'bar',
                data: {
                    labels: @json($myProductPerformanceChartData['labels'] ?? []),
                    datasets: (@json($myProductPerformanceChartData['datasets'] ?? [])).map((dataset, index) => ({
                        label: dataset.label,
                        data: dataset.data,
                        backgroundColor: index % 2 === 0 ? chartColors.purple.bg : chartColors.blue.bg,
                        borderColor: index % 2 === 0 ? chartColors.purple.border : chartColors.blue.border,
                        borderWidth: 1,
                        borderRadius: 4,
                        hoverBackgroundColor: index % 2 === 0 ? chartColors.purple.hover : chartColors.blue.hover
                    }))
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { family: 'Inter', size: 12, weight: '500' },
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            titleFont: { family: 'Inter', size: 13, weight: '600' },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 10,
                            cornerRadius: 6,
                            boxPadding: 6,
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.raw} units`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: { family: 'Inter', size: 11, weight: '500' },
                                color: '#6b7280',
                                precision: 0,
                                padding: 8
                            },
                            grid: {
                                color: 'rgba(209, 213, 219, 0.1)',
                                drawBorder: false,
                                drawTicks: false
                            }
                        },
                        x: {
                            stacked: true,
                            ticks: {
                                font: { family: 'Inter', size: 11, weight: '500' },
                                color: '#6b7280',
                                maxRotation: 45,
                                minRotation: 45,
                                padding: 8
                            },
                            grid: { display: false }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutCubic'
                    }
                }
            });
        </script>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
            .chart-container {
                position: relative;
                transition: all 0.3s ease;
                border-radius: 12px;
                background: linear-gradient(145deg, #ffffff, #f8fafc);
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            }
            .chart-container:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            }
        </style>
    @endpush
</x-app-layout>
