<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight tracking-tight">
            {{ __('Financial Report') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                    <!-- Form -->
                    <form method="GET" action="{{ route('reports.generate') }}" class="mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="start_date" class="block text-sm font-semibold text-gray-700">Start Date</label>
                                <input type="date" name="start_date" id="start_date"
                                       value="{{ request('start_date') }}"
                                       class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-semibold text-gray-700">End Date</label>
                                <input type="date" name="end_date" id="end_date"
                                       value="{{ request('end_date') }}"
                                       class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150">
                            </div>
                            <div class="flex items-end">
                                <button type="submit"
                                        class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition duration-200 flex items-center">
                                    <span>Generate Report</span>
                                    <svg class="w-5 h-5 ml-2 hidden animate-spin" id="spinner" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Report content -->
                    @if(request()->has('start_date'))
                        <div class="mt-8">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">
                                Report from {{ Carbon\Carbon::parse($startDate)->format('d F Y') }} to {{ Carbon\Carbon::parse($endDate)->format('d F Y') }}
                            </h3>

                            <!-- Customer Financials -->
                            <div class="mb-12 bg-white p-6 rounded-lg shadow-md">
                                <h4 class="font-semibold text-lg text-gray-800 mb-4">Customer Financial Summary</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-indigo-50">
                                            <tr>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Customer</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Total Revenue</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Total Paid</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Total Due</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($customerFinancials as $customer)
                                                <tr class="hover:bg-gray-50 transition duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->name }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($customer->total_revenue, 2) }} Tk</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($customer->total_paid, 2) }} Tk</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($customer->total_due, 2) }} Tk</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Charts -->
                            <div class="mb-12 bg-gradient-to-r from-indigo-50 to-blue-50 p-6 rounded-lg shadow-md">
                                <h4 class="font-semibold text-lg text-gray-800 mb-6">Financial Overview</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                    <!-- Revenue Chart -->
                                    <div class="bg-white p-4 rounded-lg shadow-sm">
                                        <canvas id="revenueChart" class="w-full h-72"></canvas>
                                    </div>
                                    <!-- Paid Chart -->
                                    <div class="bg-white p-4 rounded-lg shadow-sm">
                                        <canvas id="paidChart" class="w-full h-72"></canvas>
                                    </div>
                                    <!-- Due Chart -->
                                    <div class="bg-white p-4 rounded-lg shadow-sm">
                                        <canvas id="dueChart" class="w-full h-72"></canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- Transactions -->
                            <div class="mb-12 bg-white p-6 rounded-lg shadow-md">
                                <h4 class="font-semibold text-lg text-gray-800 mb-4">Transactions ({{ $transactions->count() }})</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-indigo-50">
                                            <tr>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Date</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Customer</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Product</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($transactions as $transaction)
                                                <tr class="hover:bg-gray-50 transition duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->transaction_date->format('d F Y') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->customer->name }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->variant->product->name }} ({{ $transaction->variant->name }})</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($transaction->total_amount, 2) }} Tk</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Payments -->
                            <div class="mb-12 bg-white p-6 rounded-lg shadow-md">
                                <h4 class="font-semibold text-lg text-gray-800 mb-4">Payments ({{ $payments->count() }})</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-indigo-50">
                                            <tr>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Date</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Customer</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Receiver</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($payments as $payment)
                                                <tr class="hover:bg-gray-50 transition duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment->payment_date->format('d F Y') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment->customer->name }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment->receiver->name }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($payment->amount, 2) }} Tk</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Customers -->
                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <h4 class="font-semibold text-lg text-gray-800 mb-4">New Customers ({{ $customers->count() }})</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-indigo-50">
                                            <tr>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Name</th>
                                                <th class="px-6 py-4 text-left text-xs font-semibold text-indigo-700 uppercase tracking-wider">Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($customers as $customer)
                                                <tr class="hover:bg-gray-50 transition duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->name }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->created_at->format('d F Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Chart.js Script -->
                        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
                        <script>
                            // Revenue Chart
                            new Chart(document.getElementById('revenueChart'), {
                                type: 'bar',
                                data: {
                                    labels: @json($chartData['labels']),
                                    datasets: [{
                                        label: 'Total Revenue',
                                        data: @json($chartData['revenue']),
                                        backgroundColor: 'rgba(99, 102, 241, 0.7)',
                                        borderColor: 'rgba(99, 102, 241, 1)',
                                        borderWidth: 2,
                                        borderRadius: 8
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            title: {
                                                display: true,
                                                text: 'Amount (Tk)',
                                                font: { size: 14, weight: 'bold' }
                                            },
                                            ticks: {
                                                callback: function(value) {
                                                    return value.toFixed(2) + ' Tk';
                                                }
                                            }
                                        },
                                        x: {
                                            title: {
                                                display: true,
                                                text: 'Customer',
                                                font: { size: 14, weight: 'bold' }
                                            }
                                        }
                                    },
                                    plugins: {
                                        legend: {
                                            display: true,
                                            position: 'top',
                                            labels: { font: { size: 12, weight: 'bold' } }
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + ' Tk';
                                                }
                                            }
                                        }
                                    },
                                    animation: {
                                        duration: 1000,
                                        easing: 'easeOutCubic'
                                    }
                                }
                            });

                            // Paid Chart
                            new Chart(document.getElementById('paidChart'), {
                                type: 'bar',
                                data: {
                                    labels: @json($chartData['labels']),
                                    datasets: [{
                                        label: 'Total Paid',
                                        data: @json($chartData['paid']),
                                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                                        borderColor: 'rgba(16, 185, 129, 1)',
                                        borderWidth: 2,
                                        borderRadius: 8
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            title: {
                                                display: true,
                                                text: 'Amount (Tk)',
                                                font: { size: 14, weight: 'bold' }
                                            },
                                            ticks: {
                                                callback: function(value) {
                                                    return value.toFixed(2) + ' Tk';
                                                }
                                            }
                                        },
                                        x: {
                                            title: {
                                                display: true,
                                                text: 'Customer',
                                                font: { size: 14, weight: 'bold' }
                                            }
                                        }
                                    },
                                    plugins: {
                                        legend: {
                                            display: true,
                                            position: 'top',
                                            labels: { font: { size: 12, weight: 'bold' } }
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + ' Tk';
                                                }
                                            }
                                        }
                                    },
                                    animation: {
                                        duration: 1000,
                                        easing: 'easeOutCubic'
                                    }
                                }
                            });

                            // Due Chart
                            new Chart(document.getElementById('dueChart'), {
                                type: 'bar',
                                data: {
                                    labels: @json($chartData['labels']),
                                    datasets: [{
                                        label: 'Total Due',
                                        data: @json($chartData['due']),
                                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                                        borderColor: 'rgba(239, 68, 68, 1)',
                                        borderWidth: 2,
                                        borderRadius: 8
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            title: {
                                                display: true,
                                                text: 'Amount (Tk)',
                                                font: { size: 14, weight: 'bold' }
                                            },
                                            ticks: {
                                                callback: function(value) {
                                                    return value.toFixed(2) + ' Tk';
                                                }
                                            }
                                        },
                                        x: {
                                            title: {
                                                display: true,
                                                text: 'Customer',
                                                font: { size: 14, weight: 'bold' }
                                            }
                                        }
                                    },
                                    plugins: {
                                        legend: {
                                            display: true,
                                            position: 'top',
                                            labels: { font: { size: 12, weight: 'bold' } }
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + ' Tk';
                                                }
                                            }
                                        }
                                    },
                                    animation: {
                                        duration: 1000,
                                        easing: 'easeOutCubic'
                                    }
                                }
                            });

                            // Show spinner on form submit
                            document.querySelector('form').addEventListener('submit', function() {
                                document.getElementById('spinner').classList.remove('hidden');
                            });
                        </script>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>