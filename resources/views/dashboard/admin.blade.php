<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ config('app.name') }} {{ __('Management Dashboard') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('reports.generate') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Generate Report
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
                <!-- Revenue Card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                                <p class="text-2xl font-bold mt-1">৳{{ number_format($totalRevenue, 2) }}</p>
                                <p class="text-sm text-gray-500">
                                    <span class="{{ $revenueGrowth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $revenueGrowth >= 0 ? '↑' : '↓' }} {{ abs($revenueGrowth) }}%
                                    </span> from last month
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
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

                <!-- Customers Card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Customers</p>
                                <p class="text-2xl font-bold mt-1">{{ $totalCustomers }}</p>
                                <p class="text-sm text-gray-500">{{ $newCustomers }} new this month</p>
                            </div>
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                        <a href="{{ route('customers.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center justify-between">
                            <span>Manage customers</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Payments Card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Payments</p>
                                <p class="text-2xl font-bold mt-1">৳{{ number_format($totalPayments, 2) }}</p>
                                <p class="text-sm text-gray-500">৳{{ number_format($outstandingPayments, 2) }} outstanding</p>
                            </div>
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                        <a href="{{ route('payments.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center justify-between">
                            <span>View all payments</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Staff Card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Active Staff</p>
                                <p class="text-2xl font-bold mt-1">{{ $totalStaff }}</p>
                                <p class="text-sm text-gray-500">{{ $activeStaff }} with active customers</p>
                            </div>
                            <div class="p-3 rounded-full bg-amber-100 text-amber-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                        <a href="{{ route('staff.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center justify-between">
                            <span>Manage staff</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Revenue Chart -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Revenue Overview (Last 12 Months)</h3>
                    <div class="h-80">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Customer Growth Chart -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Growth (Last 12 Months)</h3>
                    <div class="h-80">
                        <canvas id="customerGrowthChart"></canvas>
                    </div>
                </div>

                <!-- Product Performance Chart -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Selling Products</h3>
                    <div class="h-80">
                        <canvas id="productPerformanceChart"></canvas>
                    </div>
                </div>

                <!-- Payment Collection Chart -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Collection (Last 12 Months)</h3>
                    <div class="h-80">
                        <canvas id="paymentCollectionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Data Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Recent Transactions -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Transactions</h3>
                            <a href="{{ route('transactions.index') }}" class="text-sm text-blue-600 hover:underline">View All</a>
                        </div>
                        <div class="space-y-4">
                            @foreach($recentTransactions as $transaction)
                                <div class="p-4 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $transaction->customer->name }}</h4>
                                            <div class="text-sm text-gray-500">
                                                {{ $transaction->variant->product->name }} - {{ $transaction->variant->name }}
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium">৳{{ number_format($transaction->total_amount, 2) }}</div>
                                            <div class="text-xs {{ $transaction->is_paid ? 'text-green-600' : 'text-amber-600' }}">
                                                {{ $transaction->is_paid ? 'Paid' : 'Due' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Recent Payments -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Payments</h3>
                            <a href="{{ route('payments.index') }}" class="text-sm text-blue-600 hover:underline">View All</a>
                        </div>
                        <div class="space-y-4">
                            @foreach($recentPayments as $payment)
                                <div class="p-4 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $payment->customer->name }}</h4>
                                            <div class="text-sm text-gray-500">
                                                Collected by {{ $payment->receiver->name }}
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-green-600">৳{{ number_format($payment->amount, 2) }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ $payment->payment_date->format('d M Y') }}
                                            </div>
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
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Upcoming Visits</h3>
                            <a href="{{ route('visits.index') }}" class="text-sm text-blue-600 hover:underline">View All</a>
                        </div>
                        <div class="space-y-4">
                            @foreach($upcomingVisits as $visit)
                                <div class="p-4 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $visit->purpose }}</h4>
                                            <div class="text-sm text-gray-500 mt-1">
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
                                    <div class="mt-3 text-sm">
                                    <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                        {{ $visit->assignedTo->name }}
                                    </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Due Transactions Section -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 mb-8">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Due Transactions</h3>
                        <a href="{{ route('transactions.index', ['status' => 'due']) }}" class="text-sm text-blue-600 hover:underline">View All</a>
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
                            @foreach($dueTransactions as $transaction)
                                <tr class="hover:bg-gray-50">
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
                                        <a href="{{ route('payments.create', ['transaction_id' => $transaction->id]) }}" class="text-blue-600 hover:text-blue-800">Collect</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Top Selling Products -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Top Selling Products</h3>
                        <a href="{{ route('products.index') }}" class="text-sm text-blue-600 hover:underline">View All</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        @foreach($topSellingVariants as $variant)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center mb-2">
                                    <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 mr-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $variant->product->name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $variant->name }}</p>
                                    </div>
                                </div>
                                <div class="text-sm">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-500">Price:</span>
                                        <span class="font-medium">৳{{ number_format($variant->default_price, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center mt-1">
                                        <span class="text-gray-500">Sold:</span>
                                        <span class="font-medium">{{ $variant->sold_quantity }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: @json($revenueChartData['labels']),
                    datasets: [{
                        label: 'Revenue',
                        data: @json($revenueChartData['data']),
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: 'rgba(16, 185, 129, 1)',
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
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return '৳' + context.raw.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '৳' + value.toLocaleString();
                                }
                            },
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Customer Growth Chart
            const customerGrowthCtx = document.getElementById('customerGrowthChart').getContext('2d');
            const customerGrowthChart = new Chart(customerGrowthCtx, {
                type: 'bar',
                data: {
                    labels: @json($customerGrowthChartData['labels']),
                    datasets: [{
                        label: 'New Customers',
                        data: @json($customerGrowthChartData['data']),
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            },
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Product Performance Chart
            const productPerformanceCtx = document.getElementById('productPerformanceChart').getContext('2d');
            const productPerformanceChart = new Chart(productPerformanceCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($productPerformanceChartData['labels']),
                    datasets: [{
                        data: @json($productPerformanceChartData['data']),
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(245, 158, 11, 0.7)',
                            'rgba(139, 92, 246, 0.7)',
                            'rgba(244, 63, 94, 0.7)'
                        ],
                        borderColor: [
                            'rgba(16, 185, 129, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(139, 92, 246, 1)',
                            'rgba(244, 63, 94, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });

            // Payment Collection Chart
            const paymentCollectionCtx = document.getElementById('paymentCollectionChart').getContext('2d');
            const paymentCollectionChart = new Chart(paymentCollectionCtx, {
                type: 'bar',
                data: {
                    labels: @json($paymentCollectionChartData['labels']),
                    datasets: [{
                        label: 'Payments Collected',
                        data: @json($paymentCollectionChartData['data']),
                        backgroundColor: 'rgba(139, 92, 246, 0.7)',
                        borderColor: 'rgba(139, 92, 246, 1)',
                        borderWidth: 1
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
                            callbacks: {
                                label: function(context) {
                                    return '৳' + context.raw.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '৳' + value.toLocaleString();
                                }
                            },
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
