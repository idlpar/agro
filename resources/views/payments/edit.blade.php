<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Edit Payment #{{ $payment->id }}
                </h2>
                <div class="flex items-center space-x-2 mt-2">
                    <a href="{{ route('payments.index') }}" class="text-blue-500 hover:text-blue-700 text-sm">
                        All Payments
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-600 text-sm">Edit</span>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('payments.show', $payment->id) }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-lg font-medium text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    View
                </a>
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Edit Payment') }} | {{ config('app.name') }}
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl">
                <form method="POST" action="{{ route('payments.update', $payment->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="p-6 sm:p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Customer -->
                            <div>
                                <x-label for="user_id" :value="__('Customer')" class="block text-sm font-medium text-gray-700 mb-1" />
                                <select id="user_id" name="user_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('user_id', $payment->user_id) == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Payment Date -->
                            <div>
                                <x-label for="payment_date" :value="__('Payment Date')" class="block text-sm font-medium text-gray-700 mb-1" />
                                <div class="mt-1 relative rounded-lg shadow-sm">
                                    <input type="date" id="payment_date" name="payment_date" value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" required
                                           class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg">
                                </div>
                                @error('payment_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Amount -->
                            <div>
                                <x-label for="amount" :value="__('Amount (Tk)')" class="block text-sm font-medium text-gray-700 mb-1" />
                                <div class="mt-1 relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">৳</span>
                                    </div>
                                    <input type="number" step="0.01" min="0.01" id="amount" name="amount" value="{{ old('amount', $payment->amount) }}" required
                                           class="block w-full pl-9 pr-12 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Tk</span>
                                    </div>
                                </div>
                                @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Original Payment Reference -->
                            <div>
                                <x-label :value="__('Original Payment')" class="block text-sm font-medium text-gray-700 mb-1" />
                                <div class="mt-1 p-2 bg-gray-50 rounded-lg text-sm border border-gray-200">
                                    #{{ $payment->id }} - {{ $payment->payment_date->format('M d, Y') }}
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <x-label for="notes" :value="__('Notes (Optional)')" class="block text-sm font-medium text-gray-700 mb-1" />
                                <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('notes', $payment->notes) }}</textarea>
                                @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Customer Due Information -->
                        <div class="mt-8">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Customer Due Transactions</h3>
                            <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                                <div class="p-4 border-b border-gray-200 bg-gray-100">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-700">Total Due: {{ number_format($payment->customer->dueTransactions->sum('total_amount'), 2) }} Tk</span>
                                        <span class="text-xs text-gray-500">{{ $payment->customer->dueTransactions->count() }} transactions</span>
                                    </div>
                                </div>
                                <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                                    @foreach($payment->customer->dueTransactions()->orderBy('transaction_date')->get() as $transaction)
                                        <div class="p-4 hover:bg-gray-100 transition-colors">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <div class="flex justify-between">
                                                        <span class="text-sm font-medium text-gray-900">
                                                            {{ $transaction->variant->product->name }} ({{ $transaction->variant->name }})
                                                        </span>
                                                        <span class="text-sm font-semibold text-gray-900">
                                                            {{ number_format($transaction->total_amount, 2) }} Tk
                                                        </span>
                                                    </div>
                                                    <div class="mt-1 text-xs text-gray-500">
                                                        {{ $transaction->transaction_date->format('d M Y') }} •
                                                        {{ $transaction->quantity }} × {{ number_format($transaction->unit_price, 2) }} Tk
                                                    </div>
                                                    @if($payment->transactions->contains($transaction))
                                                        <div class="mt-2">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                            Allocated: {{ number_format($payment->transactions->find($transaction)->pivot->allocated_amount, 2) }} Tk
                                                        </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Allocate Transactions Section -->
                        <div class="mt-8">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Allocate Payment to Transactions</h3>
                            <div class="bg-white border border-gray-200 rounded-xl p-6">
                                @if($payment->transactions->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Amount</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Allocated</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($payment->transactions as $transaction)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">{{ $transaction->variant->product->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $transaction->transaction_date->format('d M Y') }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ number_format($transaction->total_amount, 2) }} Tk
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ number_format($transaction->pivot->allocated_amount, 2) }} Tk
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <button type="button" class="text-blue-600 hover:text-blue-900">Edit</button>
                                                        <button type="button" class="ml-3 text-red-600 hover:text-red-900">Remove</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <h4 class="mt-3 text-lg font-medium text-gray-900">No transactions allocated</h4>
                                        <p class="mt-1 text-gray-500">Select transactions below to allocate this payment</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-8 flex items-center justify-end space-x-4">
                            <a href="{{ route('payments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-lg font-medium text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 border border-transparent rounded-lg font-medium text-xs text-white uppercase tracking-widest shadow-sm hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                Update Payment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Customer selection change handler
                const customerSelect = document.getElementById('user_id');
                customerSelect.addEventListener('change', function() {
                    const customerId = this.value;
                    if (!customerId) return;

                    // Here you would typically fetch customer's due transactions via AJAX
                    // and update the UI accordingly
                    console.log('Customer changed to:', customerId);
                });
            });
        </script>
    @endpush
</x-app-layout>
