<x-app-layout>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ auth()->user()->name }}'s Dashboard
                </h2>
                <p class="text-sm text-gray-500 mt-1">Your performance overview</p>
            </div>
            <div class="flex flex-wrap space-x-6">
                <a href="{{ route('transactions.create') }}" class="mb-2 md:mb-0 inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-amber-600 rounded-lg text-white font-medium shadow-md hover:from-amber-600 hover:to-amber-700 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Sale
                </a>
                <a href="{{ route('payments.create') }}" class="mb-2 md:mb-0 inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 rounded-lg text-white font-medium shadow-md hover:from-green-600 hover:to-green-700 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Collect Payment
                </a>
                <a href="{{ route('payments.dueList') }}" class="mb-2 md:mb-0 inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg text-white font-medium shadow-md hover:from-yellow-600 hover:to-yellow-700 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Due List
                </a>
                <a href="{{ route('visits.create') }}" class="mb-2 md:mb-0 inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white font-medium shadow-md hover:from-blue-600 hover:to-blue-700 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Schedule Visit
                </a>
                <a href="{{ route('reports.generate') }}" class="mb-2 md:mb-0 inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg text-white font-medium shadow-md hover:from-indigo-600 hover:to-indigo-700 transition-all">
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">


                <!-- Total Revenue Card -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-sm overflow-hidden border border-green-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-800">This Month's Revenue</p>
                                <p class="text-2xl font-bold mt-1 text-gray-900">৳{{ number_format($myThisMonthRevenue, 2) }}</p>
                                <div class="flex items-center mt-2">
                                    <span class="text-sm {{ $myRevenueGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $myRevenueGrowth >= 0 ? '↑' : '↓' }} {{ abs($myRevenueGrowth) }}%
                                    </span>
                                    <span class="text-xs text-gray-500 ml-2">vs last month (৳{{ number_format($myLastMonthRevenue, 2) }})</span>
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

                <!-- Payments Card -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-sm overflow-hidden border border-purple-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-purple-800">This Month's Payments</p>
                                <p class="text-2xl font-bold mt-1 text-gray-900">৳{{ number_format($myThisMonthPayments, 2) }}</p>
                                <div class="flex items-center mt-2">
                                    <span class="text-sm {{ $myPaymentGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $myPaymentGrowth >= 0 ? '↑' : '↓' }} {{ abs($myPaymentGrowth) }}%
                                    </span>
                                    <span class="text-xs text-gray-500 ml-2">vs last month (৳{{ number_format($myLastMonthPayments, 2) }})</span>
                                </div>
                            </div>
                            <div class="p-3 rounded-full bg-white text-purple-600 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white/50 px-6 py-3 border-t border-purple-100/50">
                        <a href="{{ route('payments.index') }}" class="text-sm font-medium text-purple-700 hover:text-purple-900 flex items-center justify-between">
                            <span>View all payments</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Total Payments Card -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-sm overflow-hidden border border-purple-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-purple-800">Total Payments</p>
                                <p class="text-2xl font-bold mt-1 text-gray-900">৳{{ number_format($myTotalPayments, 2) }}</p>
                                <div class="flex items-center mt-2">
                                    <span class="text-sm text-purple-600">
                                        ৳{{ number_format($myThisMonthPayments, 2) }} this month
                                    </span>
                                </div>
                            </div>
                            <div class="p-3 rounded-full bg-white text-purple-600 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white/50 px-6 py-3 border-t border-purple-100/50">
                        <a href="{{ route('payments.index') }}" class="text-sm font-medium text-purple-700 hover:text-purple-900 flex items-center justify-between">
                            <span>View all payments</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions and Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty/Unit</th>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $transaction->quantity }} {{ $transaction->variant->unit }}
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
                            <a href="{{ route('transactions.create') }}" class="block p-4 bg-gradient-to-r from-amber-50 to-amber-100 rounded-lg border border-amber-200 hover:shadow-md transition-shadow">
                                <div class="flex items-center">
                                    <div class="p-2 rounded-full bg-amber-100 text-amber-600 mr-4">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Create New Sale</h4>
                                        <p class="text-sm text-gray-500">Record a new transaction</p>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('payments.create') }}" class="block p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg border border-green-200 hover:shadow-md transition-shadow">
                                <div class="flex items-center">
                                    <div class="p-2 rounded-full bg-green-100 text-green-600 mr-4">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Collect Payment</h4>
                                        <p class="text-sm text-gray-500">Record a payment from customer</p>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('visits.create') }}" class="block p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg border border-blue-200 hover:shadow-md transition-shadow">
                                <div class="flex items-center">
                                    <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-4">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Schedule Visit</h4>
                                        <p class="text-sm text-gray-500">Plan a customer visit</p>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('customers.create') }}" class="block p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg border border-purple-200 hover:shadow-md transition-shadow">
                                <div class="flex items-center">
                                    <div class="p-2 rounded-full bg-purple-100 text-purple-600 mr-4">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Add New Customer</h4>
                                        <p class="text-sm text-gray-500">Register a new customer</p>
                                    </div>
                                </div>
                            </a>
                        </div>
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

                <!-- Payment Collection Chart -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Payment Collection</h3>
                        <div class="h-64">
                            <canvas id="myPaymentCollectionChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Historical Product Sales Chart -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Historical Product Sales</h3>
                        <div class="h-64">
                            <canvas id="myHistoricalProductSalesChart"></canvas>
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
                                            <h4 class="font-medium text-gray-900">{{ $product->product->name }} - {{ $product->name }}</h4>
                                            <span class="text-xs font-medium bg-green-100 text-green-800 px-2 py-0.5 rounded-full">
                                                {{ $product->sold_quantity_last_month }} units last month
                                            </span>
                                        </div>
                                        <div class="mt-1 flex justify-between items-center">
                                            <span class="text-xs text-gray-500">
                                                ৳{{ number_format($product->default_price, 2) }}
                                            </span>
                                            <span class="text-xs font-medium {{ $product->sold_quantity_this_month >= $product->sold_quantity_last_month ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $product->sold_quantity_this_month >= $product->sold_quantity_last_month ? '↑' : '↓' }}
                                                {{ abs($product->sold_quantity_this_month - $product->sold_quantity_last_month) }}
                                                vs this month
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $myTopProducts->links() }}
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
                            @foreach($myUpcomingVisits as $visit)
                                <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $visit->customer->name }}</h4>
                                            <div class="text-sm text-gray-500">
                                                {{ $visit->scheduled_date->format('M d, Y h:i A') }}
                                            </div>
                                            @if($visit->purpose)
                                                <div class="mt-1 text-xs text-gray-600">
                                                    {{ Str::limit($visit->purpose, 50) }}
                                                </div>
                                            @endif
                                        </div>
                                        <span class="text-xs font-medium px-2 py-1 rounded-full
                                            {{ $visit->status === 'scheduled' ? 'bg-blue-100 text-blue-800' :
                                               ($visit->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($visit->status) }}
                                        </span>
                                    </div>
                                    <div class="mt-3 flex space-x-2">
                                        <a href="{{ route('visits.edit', $visit) }}" class="flex-1 text-center py-1 text-xs bg-white border border-gray-300 text-gray-700 rounded hover:bg-gray-50 transition-colors">
                                            Edit
                                        </a>
                                        <a href="{{ route('visits.show', $visit) }}" class="flex-1 text-center py-1 text-xs bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition-colors">
                                            Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
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
{{--                    <div class="mt-4">--}}
{{--                        {{ $myDueTransactions->links() }}--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
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

                // Payment Collection Chart
                const paymentCollectionCtx = document.getElementById('myPaymentCollectionChart').getContext('2d');
                const paymentCollectionChart = new Chart(paymentCollectionCtx, {
                    type: 'line',
                    data: {
                        labels: @json($myPaymentCollectionChartData['labels'] ?? []),
                        datasets: [{
                            label: 'Monthly Payments',
                            data: @json($myPaymentCollectionChartData['data'] ?? []),
                            backgroundColor: chartColors.purple.bg,
                            borderColor: chartColors.purple.border,
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: chartColors.purple.border,
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

                // Historical Product Sales Chart
                const historicalProductSalesCtx = document.getElementById('myHistoricalProductSalesChart').getContext('2d');
                const historicalProductSalesChart = new Chart(historicalProductSalesCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($myHistoricalProductSalesChartData['labels']['monthly'] ?? []),
                        datasets: (@json($myHistoricalProductSalesChartData['datasets'] ?? [])).map((dataset, index) => ({
                            label: dataset.label,
                            data: dataset.monthly,
                            backgroundColor: index % 2 === 0 ? chartColors.blue.bg : chartColors.green.bg,
                            borderColor: index % 2 === 0 ? chartColors.blue.border : chartColors.green.border,
                            borderWidth: 1,
                            borderRadius: 4,
                            hoverBackgroundColor: index % 2 === 0 ? chartColors.blue.hover : chartColors.green.hover
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
