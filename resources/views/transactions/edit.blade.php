<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Edit Transaction') }} #{{ $transaction->id }}
                </h2>
                <nav class="flex items-center space-x-2 text-sm mt-1">
                    <a href="{{ route('transactions.index') }}" class="text-blue-600 hover:text-blue-800">
                        {{ __('Transactions') }}
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-600">
                        {{ __('Edit') }}
                    </span>
                </nav>
            </div>
            <div class="flex items-center space-x-4">
                <span class="px-3 py-1 inline-flex text-xs leading-4 font-semibold rounded-full
                    {{ $transaction->is_paid ? 'bg-green-100 text-green-800' : ($transaction->isPartiallyPaid() ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                    {{ $transaction->payment_status }}
                </span>
                <span class="text-sm text-gray-500">
                    {{ $transaction->transaction_date->format('M d, Y') }}
                </span>
                <a href="{{ route('transactions.index') }}"
                   class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Edit Transaction') }} | {{ config('app.name') }}
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Customer Summary -->
            <div class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-sm font-medium text-gray-700">{{ __('Customer Summary') }}</h3>
                </div>
                <div class="p-4 grid grid-cols-3 gap-4 text-center">
                    <div class="bg-gray-50 p-3 rounded border border-gray-100">
                        <span class="text-xs text-gray-600">{{ __('Total') }}</span>
                        <p class="text-lg font-semibold text-gray-800 mt-1">{{ number_format($customerDue + $transaction->total_amount, 2) }}</p>
                    </div>
                    <div class="bg-green-50 p-3 rounded border border-green-100">
                        <span class="text-xs text-green-600">{{ __('Paid') }}</span>
                        <p class="text-lg font-semibold text-green-700 mt-1">{{ number_format($transaction->customer->paid_amount, 2) }}</p>
                    </div>
                    <div class="bg-red-50 p-3 rounded border border-red-100">
                        <span class="text-xs text-red-600">{{ __('Due') }}</span>
                        <p class="text-lg font-semibold text-red-700 mt-1">{{ number_format($customerDue, 2) }} Tk</p>
                    </div>
                </div>
            </div>

            <!-- Main Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('transactions.update', $transaction->id) }}" id="transactionForm">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Customer -->
                            <div>
                                <x-label for="user_id" :value="__('Customer')" required />
                                <select id="user_id" name="user_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm" required>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ $transaction->user_id == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                            @if($customer->dueTransactions->count())
                                                (Due: {{ number_format($customer->dueTransactions->sum('total_amount'), 2) }} Tk)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Product Variant -->
                            <div>
                                <x-label for="product_variant_id" :value="__('Product')" required />
                                <select id="product_variant_id" name="product_variant_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm" required>
                                    @foreach($variants as $variant)
                                        <option value="{{ $variant->id }}"
                                                data-price="{{ $variant->default_price }}"
                                            {{ $transaction->product_variant_id == $variant->id ? 'selected' : '' }}>
                                            {{ $variant->product->name }} - {{ $variant->name }} ({{ number_format($variant->default_price, 2) }} Tk)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Transaction Date -->
                            <div>
                                <x-label for="transaction_date" :value="__('Transaction Date')" required />
                                <x-input id="transaction_date" class="block mt-1 w-full text-sm" type="date" name="transaction_date"
                                         :value="old('transaction_date', $transaction->transaction_date->format('Y-m-d'))" required />
                            </div>

                            <!-- Quantity -->
                            <div>
                                <x-label for="quantity" :value="__('Quantity')" required />
                                <x-input id="quantity" class="block mt-1 w-full text-sm" type="number" step="0.01" min="0.01" name="quantity"
                                         :value="old('quantity', $transaction->quantity)" required />
                            </div>

                            <!-- Unit Price -->
                            <div>
                                <x-label for="unit_price" :value="__('Unit Price (Tk)')" required />
                                <x-input id="unit_price" class="block mt-1 w-full text-sm" type="number" step="0.01" min="0" name="unit_price"
                                         :value="old('unit_price', $transaction->unit_price)" required />
                            </div>

                            <!-- Discount Amount -->
                            <div>
                                <x-label for="discount_amount" :value="__('Discount Amount (Tk)')" />
                                <x-input id="discount_amount" class="block mt-1 w-full text-sm" type="number" step="0.01" min="0" name="discount_amount"
                                         :value="old('discount_amount', $transaction->discount_amount)" />
                            </div>

                            <!-- Calculated Total -->
                            <div class="bg-blue-50 p-3 rounded border border-blue-100">
                                <x-label :value="__('Calculated Total')" />
                                <p class="text-lg font-bold text-blue-800" id="calculatedTotal">{{ number_format($transaction->total_amount, 2) }} Tk</p>
                                <p class="text-xs text-blue-600 mt-1" id="breakdown">
                                    ({{ $transaction->quantity }} × {{ number_format($transaction->unit_price, 2) }}) - {{ number_format($transaction->discount_amount, 2) }} = {{ number_format($transaction->total_amount, 2) }}
                                </p>
                            </div>

                            <!-- Payment Status -->
                            <div>
                                <x-label for="payment_status" :value="__('Payment Status')" />
                                <div class="mt-2 space-y-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" class="form-radio h-4 w-4 text-blue-600 payment-status" name="is_paid" value="0"
                                            {{ !$transaction->is_paid && !$transaction->isPartiallyPaid() ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm">Due</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" class="form-radio h-4 w-4 text-blue-600 payment-status" name="is_paid" value="1"
                                            {{ $transaction->is_paid ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm">Paid</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" class="form-radio h-4 w-4 text-blue-600 payment-status" name="is_paid" value="2"
                                            {{ $transaction->isPartiallyPaid() ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm">Partial Payment</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Partial Payment Amount -->
                            <div id="partialPaymentContainer" class="{{ $transaction->isPartiallyPaid() ? '' : 'hidden' }}">
                                <x-label for="partial_payment_amount" :value="__('Partial Payment Amount (Tk)')" />
                                <div class="relative">
                                    <x-input id="partial_payment_amount" class="block mt-1 w-full text-sm" type="number" step="0.01" min="0"
                                             name="partial_payment_amount" :value="old('partial_payment_amount', $transaction->paid_amount)" />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-xs" id="remainingAmount">
                                            Remaining: {{ number_format($transaction->total_amount - $transaction->paid_amount, 2) }} Tk
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <x-label for="notes" :value="__('Notes (Optional)')" />
                                <textarea id="notes" name="notes" rows="2" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm">{{ old('notes', $transaction->notes) }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('transactions.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <x-button class="ml-3 bg-blue-600 hover:bg-blue-700">
                                {{ __('Update Transaction') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Elements
                const productSelect = document.getElementById('product_variant_id');
                const quantityInput = document.getElementById('quantity');
                const unitPriceInput = document.getElementById('unit_price');
                const discountInput = document.getElementById('discount_amount');
                const calculatedTotal = document.getElementById('calculatedTotal');
                const breakdown = document.getElementById('breakdown');
                const paymentStatusRadios = document.querySelectorAll('.payment-status');
                const partialPaymentContainer = document.getElementById('partialPaymentContainer');
                const partialPaymentInput = document.getElementById('partial_payment_amount');
                const remainingAmountEl = document.getElementById('remainingAmount');

                // Calculate total amount with breakdown
                function calculateTotal() {
                    const quantity = parseFloat(quantityInput.value) || 0;
                    const unitPrice = parseFloat(unitPriceInput.value) || 0;
                    const discount = parseFloat(discountInput.value) || 0;
                    const total = (quantity * unitPrice) - discount;

                    calculatedTotal.textContent = total.toFixed(2) + ' Tk';
                    breakdown.textContent = `(${quantity.toFixed(2)} × ${unitPrice.toFixed(2)}) - ${discount.toFixed(2)} = ${total.toFixed(2)}`;

                    // Update partial payment max value and remaining amount
                    if (partialPaymentInput) {
                        partialPaymentInput.max = total.toFixed(2);
                        const partialPayment = parseFloat(partialPaymentInput.value) || 0;
                        const remaining = total - partialPayment;
                        remainingAmountEl.textContent = `Remaining: ${remaining.toFixed(2)} Tk`;
                    }

                    return total;
                }

                // Set default price when product is selected
                productSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption && selectedOption.dataset.price) {
                        unitPriceInput.value = selectedOption.dataset.price;
                        calculateTotal();
                    }
                });

                // Calculate total when quantity or price changes
                [quantityInput, unitPriceInput, discountInput].forEach(input => {
                    input.addEventListener('input', calculateTotal);
                });

                // Handle payment status change
                paymentStatusRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        if (this.value === '2') {
                            partialPaymentContainer.classList.remove('hidden');
                            const totalAmount = calculateTotal();
                            partialPaymentInput.max = totalAmount.toFixed(2);
                            if (!partialPaymentInput.value || parseFloat(partialPaymentInput.value) > totalAmount) {
                                partialPaymentInput.value = totalAmount.toFixed(2);
                            }
                            calculateTotal();
                        } else {
                            partialPaymentContainer.classList.add('hidden');
                        }
                    });
                });

                // Update remaining amount when partial payment changes
                if (partialPaymentInput) {
                    partialPaymentInput.addEventListener('input', function() {
                        const total = parseFloat(calculatedTotal.textContent) || 0;
                        const partialPayment = parseFloat(this.value) || 0;
                        const remaining = total - partialPayment;
                        remainingAmountEl.textContent = `Remaining: ${remaining.toFixed(2)} Tk`;
                    });
                }

                // Initial calculation
                calculateTotal();
            });
        </script>
    @endpush
</x-app-layout>
