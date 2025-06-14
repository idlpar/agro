<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Record New Transaction') }}
                </h2>
                <nav class="flex items-center space-x-2 text-sm mt-1">
                    <a href="{{ route('transactions.index') }}" class="text-blue-600 hover:text-blue-800">
                        {{ __('Transactions') }}
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-600" id="customerName">
                        {{ __('New Transaction') }}
                    </span>
                </nav>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('transactions.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('New Transaction') }} | {{ config('app.name') }}
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Customer Summary -->
            <div class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200" id="customerSummary" style="display: none;">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-medium text-gray-700">{{ __('Customer Summary') }}</h3>
                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full" id="transactionCount"></span>
                    </div>
                </div>
                <div class="p-4 grid grid-cols-3 gap-4 text-center">
                    <div class="bg-gray-50 p-3 rounded border border-gray-100">
                        <span class="text-xs text-gray-600">{{ __('Total') }}</span>
                        <p class="text-lg font-semibold text-gray-800 mt-1" id="totalTransactions">0</p>
                    </div>
                    <div class="bg-green-50 p-3 rounded border border-green-100">
                        <span class="text-xs text-green-600">{{ __('Paid') }}</span>
                        <p class="text-lg font-semibold text-green-700 mt-1" id="paidTransactions">0</p>
                    </div>
                    <div class="bg-red-50 p-3 rounded border border-red-100">
                        <span class="text-xs text-red-600">{{ __('Due') }}</span>
                        <p class="text-lg font-semibold text-red-700 mt-1" id="dueAmount">0 Tk</p>
                    </div>
                </div>
            </div>

            <!-- Main Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('transactions.store') }}" id="transactionForm">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Customer -->
                            <div>
                                <x-label for="user_id" :value="__('Customer')" required />
                                <select id="user_id" name="user_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        @php
                                            $dueAmount = $customer->dueTransactions->sum('total_amount') - $customer->dueTransactions->sum(function($t) {
                                                return $t->payments->sum('pivot.allocated_amount');
                                            });
                                        @endphp
                                        <option value="{{ $customer->id }}"
                                                data-due="{{ $dueAmount }}"
                                            {{ old('user_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                            @if($dueAmount > 0)
                                                (Due: {{ number_format($dueAmount, 2) }} Tk)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Product Variant -->
                            <div>
                                <x-label for="product_variant_id" :value="__('Product')" required />
                                <select id="product_variant_id" name="product_variant_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm" required>
                                    <option value="">Select Product</option>
                                    @foreach($variants as $variant)
                                        <option value="{{ $variant->id }}"
                                                data-default-price="{{ $variant->default_price }}"
                                            {{ old('product_variant_id') == $variant->id ? 'selected' : '' }}>
                                            {{ $variant->product->name }} - {{ $variant->name }} ({{ number_format($variant->default_price, 2) }} Tk)
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_variant_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Transaction Date -->
                            <div>
                                <x-label for="transaction_date" :value="__('Transaction Date')" required />
                                <x-input id="transaction_date" class="block mt-1 w-full text-sm" type="date" name="transaction_date" :value="old('transaction_date', now()->format('Y-m-d'))" required />
                                @error('transaction_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div>
                                <x-label for="quantity" :value="__('Quantity')" required />
                                <x-input id="quantity" class="block mt-1 w-full text-sm" type="number" step="0.01" min="0.01" name="quantity" :value="old('quantity')" required />
                                @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Unit Price -->
                            <div>
                                <x-label for="unit_price" :value="__('Unit Price (Tk)')" required />
                                <x-input id="unit_price" class="block mt-1 w-full text-sm" type="number" step="0.01" min="0" name="unit_price" :value="old('unit_price')" required />
                                @error('unit_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Discount Amount -->
                            <div>
                                <x-label for="discount_amount" :value="__('Discount Amount (Tk)')" />
                                <x-input id="discount_amount" class="block mt-1 w-full text-sm" type="number" step="0.01" min="0" name="discount_amount" :value="old('discount_amount', 0)" />
                                @error('discount_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Calculated Total -->
                            <div class="bg-blue-50 p-3 rounded border border-blue-100">
                                <x-label :value="__('Calculated Total')" />
                                <p class="text-lg font-bold text-blue-800" id="calculatedTotal">0.00 Tk</p>
                                <p class="text-xs text-blue-600 mt-1" id="breakdown">(0.00 × 0.00) - 0.00 = 0.00</p>
                            </div>

                            <!-- Payment Status -->
                            <div>
                                <x-label for="payment_status" :value="__('Payment Status')" />
                                <div class="mt-2 space-y-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" class="form-radio h-4 w-4 text-blue-600 payment-status" name="is_paid" value="0" {{ old('is_paid', '0') === '0' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm">Due</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" class="form-radio h-4 w-4 text-blue-600 payment-status" name="is_paid" value="1" {{ old('is_paid') === '1' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm">Paid</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" class="form-radio h-4 w-4 text-blue-600 payment-status" name="is_paid" value="2" {{ old('is_paid') === '2' ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm">Partial Payment</span>
                                    </label>
                                </div>
                                @error('is_paid')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Partial Payment Amount -->
                            <div id="partialPaymentContainer" class="{{ old('is_paid') == '2' ? '' : 'hidden' }}">
                                <x-label for="partial_payment_amount" :value="__('Partial Payment Amount (Tk)')" required />
                                <div class="relative">
                                    <x-input id="partial_payment_amount" class="block mt-1 w-full text-sm" type="number" step="0.01" min="0" name="partial_payment_amount" :value="old('partial_payment_amount', 0)" required />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-xs pr-6" id="remainingAmount">Remaining: 0.00 Tk</span>
                                    </div>
                                </div>
                                @error('partial_payment_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <x-label for="notes" :value="__('Notes (Optional)')" />
                                <textarea id="notes" name="notes" rows="2" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm">{{ old('notes') }}</textarea>
                                @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-button class="ml-3 bg-blue-600 hover:bg-blue-700">
                                {{ __('Save Transaction') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200" id="recentTransactions" style="display: none;">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-sm font-medium text-gray-700">{{ __('Recent Transactions') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Details</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="transactionsList">
                        <!-- Transactions will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                <div class="p-4 text-center text-sm text-gray-500" id="noTransactions" style="display: none;">
                    No transactions found for this customer
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Elements
                const customerSelect = document.getElementById('user_id');
                const productSelect = document.getElementById('product_variant_id');
                const quantityInput = document.getElementById('quantity');
                const unitPriceInput = document.getElementById('unit_price');
                const discountInput = document.getElementById('discount_amount');
                const calculatedTotal = document.getElementById('calculatedTotal');
                const breakdown = document.getElementById('breakdown');
                const customerSummary = document.getElementById('customerSummary');
                const recentTransactions = document.getElementById('recentTransactions');
                const transactionsList = document.getElementById('transactionsList');
                const noTransactions = document.getElementById('noTransactions');
                const totalTransactionsEl = document.getElementById('totalTransactions');
                const paidTransactionsEl = document.getElementById('paidTransactions');
                const dueAmountEl = document.getElementById('dueAmount');
                const transactionCountEl = document.getElementById('transactionCount');
                const customerNameEl = document.getElementById('customerName');
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
                    if (selectedOption && selectedOption.dataset.defaultPrice) {
                        unitPriceInput.value = selectedOption.dataset.defaultPrice;
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

                // Handle customer selection change
                customerSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const customerId = this.value;

                    if (customerId) {
                        // Show customer name in breadcrumb
                        customerNameEl.textContent = selectedOption.text.split('(')[0].trim();

                        // Show summary section
                        customerSummary.style.display = 'block';
                        recentTransactions.style.display = 'block';

                        // Fetch customer data via API
                        fetch(`/api/customers/${customerId}/summary-data`)
                            .then(response => {
                                if (!response.ok) throw new Error('Network response was not ok');
                                return response.json();
                            })
                            .then(data => {
                                console.log('API Response:', data); // Debug log

                                // Update summary
                                totalTransactionsEl.textContent = parseFloat(data.total_amount).toFixed(2);
                                paidTransactionsEl.textContent = parseFloat(data.paid_amount).toFixed(2);
                                dueAmountEl.textContent = parseFloat(data.due_amount).toFixed(2) + ' Tk';
                                transactionCountEl.textContent = `${data.transaction_count} transactions`;

                                // Update recent transactions
                                transactionsList.innerHTML = '';
                                if (data.recent_transactions && data.recent_transactions.length > 0) {
                                    noTransactions.style.display = 'none';
                                    data.recent_transactions.forEach(transaction => {
                                        // Convert string values to numbers
                                        const unitPrice = parseFloat(transaction.unit_price) || 0;
                                        const totalAmount = parseFloat(transaction.total_amount) || 0;
                                        const paidAmount = parseFloat(transaction.paid_amount) || 0;
                                        const dueAmount = parseFloat(transaction.due_amount) || 0;

                                        const row = document.createElement('tr');
                                        row.className = 'hover:bg-gray-50 cursor-pointer';
                                        row.setAttribute('data-href', `/transactions/${transaction.id}`);

                                        // Determine payment status
                                        let statusText, statusClass;
                                        if (transaction.is_paid) {
                                            statusText = 'Paid';
                                            statusClass = 'bg-green-100 text-green-800';
                                        } else if (paidAmount > 0) {
                                            statusText = `Partial (${paidAmount.toFixed(2)} Tk)`;
                                            statusClass = 'bg-yellow-100 text-yellow-800';
                                        } else {
                                            statusText = 'Due';
                                            statusClass = 'bg-red-100 text-red-800';
                                        }

                                        row.innerHTML = `
                            <td class="px-4 py-3 text-xs text-gray-600 whitespace-nowrap">
                                ${new Date(transaction.transaction_date).toLocaleDateString()}
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600">
                                ${transaction.product_name || 'N/A'}
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600">
                                ${transaction.variant_name || 'N/A'}
                                <div class="text-xs text-gray-400">
                                    ${transaction.quantity} × ৳${unitPrice.toFixed(2)}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600">
                                ৳${totalAmount.toFixed(2)}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-1 inline-flex text-xs rounded-full ${statusClass}">
                                    ${statusText}
                                </span>
                            </td>
                        `;

                                        row.addEventListener('click', () => {
                                            window.location.href = row.getAttribute('data-href');
                                        });

                                        transactionsList.appendChild(row);
                                    });
                                } else {
                                    noTransactions.style.display = 'block';
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching customer data:', error);
                                transactionsList.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-xs text-red-600 text-center">
                            Error loading transactions: ${error.message}
                        </td>
                    </tr>
                `;
                            });
                    } else {
                        // Hide sections if no customer selected
                        customerSummary.style.display = 'none';
                        recentTransactions.style.display = 'none';
                        customerNameEl.textContent = 'New Transaction';
                    }
                });

                // Initialize form if there are old values
                if ('{{ old('product_variant_id') }}') {
                    productSelect.value = '{{ old('product_variant_id') }}';
                    productSelect.dispatchEvent(new Event('change'));
                }

                if ('{{ old('user_id') }}') {
                    customerSelect.value = '{{ old('user_id') }}';
                    customerSelect.dispatchEvent(new Event('change'));
                }

                if ('{{ old('is_paid') }}' === '2') {
                    document.querySelector('input[name="is_paid"][value="2"]').checked = true;
                    partialPaymentContainer.classList.remove('hidden');
                }

                // Initial calculation
                calculateTotal();
            });
        </script>
    @endpush
</x-app-layout>
