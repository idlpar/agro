<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaction Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('transactions.report') }}">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Customer -->
                            <div>
                                <x-label for="customer_id" :value="__('Customer')" />
                                <select id="customer_id" name="customer_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">All Customers</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Start Date -->
                            <div>
                                <x-label for="start_date" :value="__('Start Date')" />
                                <x-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="request('start_date')" />
                            </div>

                            <!-- End Date -->
                            <div>
                                <x-label for="end_date" :value="__('End Date')" />
                                <x-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="request('end_date')" />
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-end">
                                <x-button class="w-full">
                                    {{ __('Generate Report') }}
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if($customer)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Summary: {{ $customer->name }}</h3>

                        @if($startDate && $endDate)
                            <p class="text-gray-600 mb-4">Report Period: {{ $startDate }} to {{ $endDate }}</p>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <!-- Total Transactions -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-700">Total Transactions</h4>
                                <p class="text-2xl font-bold">{{ $transactions->count() }}</p>
                            </div>

                            <!-- Total Amount -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-700">Total Amount</h4>
                                <p class="text-2xl font-bold">{{ number_format($transactions->sum('total_amount'), 2) }} Tk</p>
                            </div>

                            <!-- Total Payments -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-700">Total Payments</h4>
                                <p class="text-2xl font-bold">{{ number_format($payments->sum('amount'), 2) }} Tk</p>
                            </div>
                        </div>

                        <!-- Due Summary -->
                        <div class="bg-gray-50 p-4 rounded-lg mb-6">
                            <h4 class="font-medium text-gray-700 mb-2">Due Summary</h4>
                            @php
                                $totalDue = $transactions->where('is_paid', false)->sum('total_amount');
                                $totalPaid = $payments->sum('amount');
                                $netDue = $totalDue - $totalPaid;
                            @endphp
                            <p>Total Due: {{ number_format($totalDue, 2) }} Tk</p>
                            <p>Total Paid: {{ number_format($totalPaid, 2) }} Tk</p>
                            <p class="font-semibold {{ $netDue > 0 ? 'text-red-600' : 'text-green-600' }}">
                                Net Due: {{ number_format($netDue, 2) }} Tk
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Transactions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Transactions</h3>

                        @if($transactions->isEmpty())
                            <p class="text-gray-500">No transactions found for this customer.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->transaction_date->format('d M Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->variant->product->name }} ({{ $transaction->variant->name }})</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->quantity }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($transaction->unit_price, 2) }} Tk</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($transaction->total_amount, 2) }} Tk</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->is_paid ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $transaction->is_paid ? 'Paid' : 'Due' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payments -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Payments</h3>

                        @if($payments->isEmpty())
                            <p class="text-gray-500">No payments found for this customer.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Received By</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->payment_date->format('d M Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($payment->amount, 2) }} Tk</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->receiver->name }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $payment->notes }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
