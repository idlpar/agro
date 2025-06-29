<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $customer->name }}'s Transactions
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('payments.dueList') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    {{ __('Back to Due List') }}
                </a>
                <a href="{{ route('payments.create', ['customer_id' => $customer->id]) }}"
                   class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    {{ __('Record Payment') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-blue-800 mb-2">Customer Summary</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-white p-3 rounded shadow">
                                    <p class="text-sm text-gray-500">Total Transactions</p>
                                    <p class="text-xl font-bold">{{ $customer->transactions->count() }}</p>
                                </div>
                                <div class="bg-white p-3 rounded shadow">
                                    <p class="text-sm text-gray-500">Total Amount</p>
                                    <p class="text-xl font-bold">{{ number_format($customer->transactions->sum('total_amount'), 2) }} Tk</p>
                                </div>
                                <div class="bg-white p-3 rounded shadow">
                                    <p class="text-sm text-gray-500">Due Balance</p>
                                    <p class="text-xl font-bold text-red-600">{{ number_format($customer->transactions()->where('is_paid', false)->sum('total_amount'), 2) }} Tk</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Product
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transactions as $transaction)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $transaction->transaction_date->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $transaction->variant->product->name }} ({{ $transaction->variant->name }})
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $transaction->quantity }} × {{ $transaction->unit_price }} Tk
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        {{ number_format($transaction->total_amount, 2) }} Tk
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($transaction->is_paid)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Paid
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $transaction->paid_amount > 0 ? 'yellow' : 'red' }}-100 text-{{ $transaction->paid_amount > 0 ? 'yellow' : 'red' }}-800">
                                                {{ $transaction->paid_amount > 0 ? 'Partial' : 'Due' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('transactions.show', $transaction) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No transactions found for this customer
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($transactions->hasPages())
                        <div class="mt-4">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
