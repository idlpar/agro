<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ config('app.name') }} {{ __('Management Dashboard') }}
            </h2>
            <div class="flex space-x-2">
                <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                    New Task
                </button>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Generate Report
                </button>
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
                <!-- Total Revenue Card -->
                <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-xl shadow-lg overflow-hidden text-white">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium">Total Revenue</p>
                                <p class="text-3xl font-bold mt-2">৳{{ number_format($totalRevenue, 2) }}</p>
                                <p class="text-xs mt-1 flex items-center">
                                    <span class="bg-white/20 px-2 py-0.5 rounded-full mr-1">↑ {{ $revenueGrowth }}%</span> vs last month
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white/10 px-6 py-3 text-xs">
                        <a href="{{ route('transactions.index') }}" class="flex items-center justify-between hover:underline">
                            <span>View all transactions</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Active Customers Card -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg overflow-hidden text-white">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium">Active Customers</p>
                                <p class="text-3xl font-bold mt-2">{{ $activeCustomers }}</p>
                                <p class="text-xs mt-1">{{ $newCustomers }} new this month</p>
                            </div>
                            <div class="p-3 rounded-full bg-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white/10 px-6 py-3 text-xs">
                        <a href="{{ route('customers.index') }}" class="flex items-center justify-between hover:underline">
                            <span>Manage customers</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Outstanding Payments Card -->
                <div class="bg-gradient-to-r from-amber-600 to-amber-700 rounded-xl shadow-lg overflow-hidden text-white">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium">Outstanding Payments</p>
                                <p class="text-3xl font-bold mt-2">৳{{ number_format($outstandingPayments, 2) }}</p>
                                <p class="text-xs mt-1">{{ $overdueAccounts }} overdue accounts</p>
                            </div>
                            <div class="p-3 rounded-full bg-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white/10 px-6 py-3 text-xs">
                        <a href="{{ route('payments.index') }}" class="flex items-center justify-between hover:underline">
                            <span>View payment details</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Staff Performance Card -->
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl shadow-lg overflow-hidden text-white">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium">Active Staff</p>
                                <p class="text-3xl font-bold mt-2">{{ User::staff()->count() }}</p>
                                <p class="text-xs mt-1">View performance reports</p>
                            </div>
                            <div class="p-3 rounded-full bg-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white/10 px-6 py-3 text-xs">
                        <a href="{{ route('staff.index') }}" class="flex items-center justify-between hover:underline">
                            <span>Manage staff</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Charts and Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Revenue Chart -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Revenue Overview</h3>
                            <div class="flex space-x-2">
                                <select class="text-sm border-gray-200 rounded-md focus:ring-green-500 focus:border-green-500">
                                    <option>This Month</option>
                                    <option>Last Month</option>
                                    <option>This Year</option>
                                </select>
                            </div>
                        </div>
                        <div class="h-80">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6">Quick Actions</h3>
                        <div class="space-y-4">
                            <a href="{{ route('transactions.create') }}" class="block p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-300 border border-green-100">
                                <div class="flex items-center">
                                    <div class="p-2 bg-green-100 rounded-lg mr-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800">Record New Sale</h4>
                                        <p class="text-sm text-gray-500">Create a new transaction</p>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('payments.create') }}" class="block p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-300 border border-blue-100">
                                <div class="flex items-center">
                                    <div class="p-2 bg-blue-100 rounded-lg mr-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800">Record Payment</h4>
                                        <p class="text-sm text-gray-500">Accept customer payment</p>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('visits.create') }}" class="block p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors duration-300 border border-purple-100">
                                <div class="flex items-center">
                                    <div class="p-2 bg-purple-100 rounded-lg mr-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800">Schedule Visit</h4>
                                        <p class="text-sm text-gray-500">Plan farm visit</p>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('products.create') }}" class="block p-4 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors duration-300 border border-amber-100">
                                <div class="flex items-center">
                                    <div class="p-2 bg-amber-100 rounded-lg mr-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800">Add Product</h4>
                                        <p class="text-sm text-gray-500">New inventory item</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Due Payments and Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Due Payments -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Due Payments</h3>
                            <a href="{{ route('payments.index') }}" class="text-sm text-green-600 hover:underline">View All</a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($duePayments as $payment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-medium">
                                                    {{ substr($payment->customer->name, 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $payment->customer->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $payment->customer->phone }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $payment->invoice_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="{{ $payment->isOverdue ? 'text-red-600' : 'text-gray-500' }}">
                                                @if($payment->due_date)
                                                    {{ $payment->due_date->format('d M Y') }}
                                                    @if($payment->isOverdue)
                                                        <span class="ml-1 text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded-full">
                                                            Overdue
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-gray-400 italic">No due date</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">৳{{ number_format($payment->amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $payment->status === 'paid' ? 'bg-green-100 text-green-800' :
                                                   ($payment->isOverdue ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                                {{ $payment->status === 'paid' ? 'Paid' : ($payment->isOverdue ? 'Overdue' : 'Pending') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('payments.edit', $payment) }}" class="text-green-600 hover:text-green-900 mr-3">Collect</a>
                                            @if($payment->transaction)
                                                <a href="{{ route('transactions.show', $payment->transaction) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            @else
                                                <span class="text-gray-400 italic">No transaction</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Visits -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Upcoming Visits</h3>
                            <a href="{{ route('visits.index') }}" class="text-sm text-green-600 hover:underline">View All</a>
                        </div>

                        <div class="space-y-4">
                            @foreach($upcomingVisits as $visit)
                                <div class="p-4 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors duration-300">
                                    <div class="flex justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $visit->purpose }}</h4>
                                            <div class="flex items-center mt-1 text-sm text-gray-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ $visit->location }}
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-medium {{ $visit->isToday ? 'text-green-600' : 'text-gray-500' }}">
                                                {{ $visit->scheduled_date->format('d M') }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ $visit->scheduled_date->format('h:i A') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3 flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-sm font-medium">
                                                {{ substr($visit->assignedTo->name, 0, 1) }}
                                            </div>
                                            <div class="ml-2 text-sm text-gray-500">{{ $visit->assignedTo->name }}</div>
                                        </div>

                                        <div class="flex space-x-2">
                                            <a href="{{ route('visits.edit', $visit) }}" class="text-xs bg-white border border-gray-200 px-2 py-1 rounded hover:bg-gray-100">Reschedule</a>
                                            <a href="{{ route('visits.show', $visit) }}" class="text-xs bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700">Details</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <a href="{{ route('visits.create') }}" class="block p-3 text-center border-2 border-dashed border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition-colors duration-300">
                                <div class="flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-600">Schedule New Visit</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions and Product Performance -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Transactions -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Transactions</h3>
                            <a href="{{ route('transactions.index') }}" class="text-sm text-green-600 hover:underline">View All</a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentTransactions as $transaction)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $transaction->transaction_date->format('d M') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $transaction->customer->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $transaction->customer->phone }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $transaction->products->count() }} items</div>
                                            <div class="text-xs text-gray-500">
                                                @foreach($transaction->products->take(2) as $product)
                                                    {{ $product->name }}@if(!$loop->last), @endif
                                                @endforeach
                                                @if($transaction->products->count() > 2) +{{ $transaction->products->count() - 2 }} more @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            ৳{{ number_format($transaction->total_amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $transaction->is_paid ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                                {{ $transaction->is_paid ? 'Paid' : 'Partial' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Top Selling Products -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Top Selling Products</h3>
                            <a href="{{ route('products.index') }}" class="text-sm text-green-600 hover:underline">View All</a>
                        </div>

                        <div class="space-y-4">
                            @foreach($topProducts as $product)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $product->name }}</h4>
                                            <span class="text-xs font-medium bg-green-100 text-green-800 px-2 py-0.5 rounded-full">
                                            {{ number_format($product->sold_quantity) }} sold
                                        </span>
                                        </div>
                                        <div class="mt-1 flex items-center justify-between">
                                            <div class="text-xs text-gray-500">
                                                Stock: {{ number_format($product->current_stock) }} {{ $product->unit }}
                                            </div>
                                            <div class="text-xs font-medium text-gray-900">
                                                ৳{{ number_format($product->price, 2) }}
                                            </div>
                                        </div>
                                        <div class="mt-1 w-full bg-gray-200 rounded-full h-1.5">
                                            <div class="bg-green-600 h-1.5 rounded-full" style="width: {{ min(100, ($product->sold_quantity / max(1, $product->initial_stock)) * 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-800 mb-3">Inventory Alerts</h4>
                            <div class="space-y-3">
                                @foreach($lowStockProducts as $product)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h5 class="text-sm font-medium text-gray-900">{{ $product->name }}</h5>
                                            <p class="text-xs text-gray-500">Only {{ $product->current_stock }} {{ $product->unit }} left ({{ round(($product->current_stock / max(1, $product->initial_stock)) * 100) }}% of stock)</p>
                                            <div class="mt-1 text-xs">
                                                <a href="{{ route('products.edit', $product) }}" class="text-green-600 hover:text-green-800">Restock now →</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
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
                    labels: @json($revenueChart['labels']),
                    datasets: [{
                        label: 'Revenue',
                        data: @json($revenueChart['data']),
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
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
