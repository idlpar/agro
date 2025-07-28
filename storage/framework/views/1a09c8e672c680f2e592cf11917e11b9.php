<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    <?php echo e(__('Record New Payment')); ?>

                </h2>
                <nav class="flex items-center space-x-2 text-sm mt-2">
                    <a href="<?php echo e(route('payments.index')); ?>" class="text-blue-500 hover:text-blue-700">
                        <?php echo e(__('All Payments')); ?>

                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-600"><?php echo e(__('New Payment')); ?></span>
                </nav>
            </div>
            <div>
                <a href="<?php echo e(route('payments.dueList')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-lg font-medium text-xs text-white uppercase tracking-widest shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a4 4 0 00-8 0v2H5v10h14V9h-2z" />
                    </svg>
                    <?php echo e(__('Receivables')); ?>

                </a>

                <a href="<?php echo e(route('payments.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-lg font-medium text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <?php echo e(__('Back to Payments')); ?>

                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

     <?php $__env->slot('title', null, []); ?> 
        <?php echo e(__('New Payments')); ?> | <?php echo e(config('app.name')); ?>

     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl">
                <div class="p-6 sm:p-8">
                    <form id="paymentForm" method="POST" action="<?php echo e(route('payments.store')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="space-y-6">
                            <!-- Customer Selection -->
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('Customer')); ?></label>
                                <select id="user_id" name="user_id" required
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg transition">
                                    <option value=""><?php echo e(__('Select Customer')); ?></option>
                                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($customer->id); ?>"
                                                data-name="<?php echo e($customer->name); ?>"
                                                data-phone="<?php echo e($customer->phone ?? ''); ?>"
                                                data-due="<?php echo e($customer->calculated_due_amount); ?>"
                                            <?php echo e((old('user_id') == $customer->id || (isset($selected_customer_id) && $selected_customer_id == $customer->id)) ? 'selected' : ''); ?>>
                                            <?php echo e($customer->name); ?> <?php if($customer->phone): ?> (<?php echo e($customer->phone); ?>) <?php endif; ?>
                                            <?php if($customer->calculated_due_amount > 0): ?>
                                                (Due: <?php echo e(number_format($customer->calculated_due_amount, 2)); ?> Tk)
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Payment Date -->
                                <div>
                                    <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('Payment Date')); ?></label>
                                    <div class="relative rounded-lg shadow-sm">
                                        <input type="date" id="payment_date" name="payment_date" value="<?php echo e(old('payment_date', now()->format('Y-m-d'))); ?>" required
                                               class="block w-full pl-3 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>
                                    <?php $__errorArgs = ['payment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Amount -->
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('Amount (Tk)')); ?></label>
                                    <div class="relative rounded-lg shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">৳</span>
                                        </div>
                                        <input type="text" step="0.01" min="0.01" id="amount" value="<?php echo e(old('amount', isset($prefill_amount) ? $prefill_amount : '')); ?>" required
                                               class="block w-full pl-9 pr-12 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <input type="hidden" id="amount_hidden" name="amount" value="<?php echo e(old('amount', isset($prefill_amount) ? $prefill_amount : '')); ?>">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">Tk</span>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <!-- Payment Allocation Toggle -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="hidden" name="mark_transactions_paid" value="1">
                                    <input id="mark_transactions_paid" name="mark_transactions_paid" type="checkbox" value="1" checked disabled
                                           <?php echo e(old('mark_transactions_paid', true) ? 'checked' : ''); ?>

                                           class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="mark_transactions_paid" class="font-medium text-gray-700">
                                        <?php echo e(__('Automatically allocate to due transactions')); ?>

                                    </label>
                                    <p class="text-gray-500">
                                        <?php echo e(__('System will apply payment to oldest unpaid transactions first')); ?>

                                    </p>
                                </div>
                            </div>

                            <!-- Settle All Dues Toggle -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="settle_all_dues" name="settle_all_dues" type="checkbox" value="1"
                                           <?php echo e(old('settle_all_dues') ? 'checked' : ''); ?>

                                           class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="settle_all_dues" class="font-medium text-gray-700">
                                        <?php echo e(__('Settle all previous dues with discount')); ?>

                                    </label>
                                    <p class="text-gray-500">
                                        <?php echo e(__('If checked, this payment will close all previous dues with a discount.')); ?>

                                    </p>
                                </div>
                            </div>

                            <!-- Discount Amount -->
                            <div id="discount_amount_container" class="hidden">
                                <label for="discount_amount" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('Discount Amount (Tk)')); ?></label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">৳</span>
                                    </div>
                                    <input type="text" step="0.01" min="0.01" id="discount_amount" value="<?php echo e(old('discount_amount')); ?>"
                                           class="block w-full pl-9 pr-12 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <input type="hidden" id="discount_amount_hidden" name="discount_amount" value="<?php echo e(old('discount_amount')); ?>">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Tk</span>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['discount_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('Notes (Optional)')); ?></label>
                                <textarea id="notes" name="notes" rows="3" class="block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"><?php echo e(old('notes')); ?></textarea>
                                <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Customer Due Information (Dynamic) -->
                            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 hidden" id="dueInfoContainer">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-medium text-blue-800"><?php echo e(__('Customer Due Summary')); ?></h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <?php echo e(__('Unpaid')); ?>

                                </span>
                                </div>
                                <div class="mt-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-blue-700"><?php echo e(__('Total Due Amount:')); ?></span>
                                        <span id="totalDue" class="text-lg font-bold text-blue-900">0.00 Tk</span>
                                    </div>
                                    <div class="flex justify-between items-center mt-1">
                                        <span class="text-sm text-blue-700"><?php echo e(__('Pending Transactions:')); ?></span>
                                        <span id="transactionCount" class="text-sm font-medium text-blue-900">0</span>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <h5 class="text-xs font-medium text-blue-700 uppercase tracking-wider mb-2"><?php echo e(__('Recent Transactions')); ?></h5>
                                    <div id="recentTransactions" class="space-y-3"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="button" onclick="confirmPayment()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 border border-transparent rounded-lg font-medium text-sm text-white uppercase tracking-widest shadow-sm hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?php echo e(__('Record Payment')); ?>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('styles'); ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('scripts'); ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('paymentForm');
                const customerSelect = document.getElementById('user_id');
                const dueInfoContainer = document.getElementById('dueInfoContainer');
                const totalDueElement = document.getElementById('totalDue');
                const transactionCountElement = document.getElementById('transactionCount');
                const recentTransactionsElement = document.getElementById('recentTransactions');
                const amountInput = document.getElementById('amount');
                const amountHiddenInput = document.getElementById('amount_hidden');
                const settleAllDuesCheckbox = document.getElementById('settle_all_dues');
                const discountAmountContainer = document.getElementById('discount_amount_container');
                const discountAmountInput = document.getElementById('discount_amount');
                const discountAmountHiddenInput = document.getElementById('discount_amount_hidden');

                function formatBdNumber(num) {
                    return new Intl.NumberFormat('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num);
                }

                function calculateDiscount() {
                    const selectedOption = customerSelect.options[customerSelect.selectedIndex];
                    const dueAmount = parseFloat(selectedOption.dataset.due) || 0;
                    const paymentAmount = parseFloat(amountInput.value.replace(/,/g, '')) || 0;

                    if (settleAllDuesCheckbox.checked) {
                        const discount = dueAmount - paymentAmount;
                        discountAmountInput.value = discount > 0 ? discount.toFixed(2) : 0;
                        discountAmountHiddenInput.value = discount > 0 ? discount.toFixed(2) : 0;
                    }
                }

                settleAllDuesCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        discountAmountContainer.classList.remove('hidden');
                        calculateDiscount();
                    } else {
                        discountAmountContainer.classList.add('hidden');
                        discountAmountInput.value = '';
                    }
                });

                amountInput.addEventListener('input', calculateDiscount);
                amountInput.addEventListener('blur', function(e) {
                    let value = parseFloat(e.target.value.replace(/,/g, ''));
                    if (!isNaN(value)) {
                        e.target.value = formatBdNumber(value);
                        amountHiddenInput.value = value.toFixed(2);
                    } else {
                        amountHiddenInput.value = '';
                    }
                });

                discountAmountInput.addEventListener('blur', function(e) {
                    let value = parseFloat(e.target.value.replace(/,/g, ''));
                    if (!isNaN(value)) {
                        e.target.value = formatBdNumber(value);
                        discountAmountHiddenInput.value = value.toFixed(2);
                    } else {
                        discountAmountHiddenInput.value = '';
                    }
                });

                customerSelect.addEventListener('change', function() {
                    const customerId = this.value;

                    if (!customerId) {
                        dueInfoContainer.classList.add('hidden');
                        return;
                    }

                    // Show loading state
                    dueInfoContainer.classList.remove('hidden');
                    recentTransactionsElement.innerHTML = `
                <div class="flex justify-center py-4">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm text-gray-500">Loading customer data...</span>
                </div>
            `;

                    // Fetch customer's summary data from the correct API endpoint
                    fetch(`/api/customers/${customerId}/summary-data`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('API Response:', data);

                            if (data && data.transaction_count > 0) {
                                dueInfoContainer.classList.remove('hidden');
                                totalDueElement.textContent = formatBdNumber(data.due_amount) + ' Tk';
                                transactionCountElement.textContent = data.transaction_count;

                                // Display recent transactions
                                recentTransactionsElement.innerHTML = '';
                                if (data.recent_transactions && data.recent_transactions.length > 0) {
                                    data.recent_transactions.forEach(transaction => {
                                        const transactionDate = new Date(transaction.transaction_date);
                                        const formattedDate = transactionDate.toLocaleDateString('en-US', {
                                            year: 'numeric',
                                            month: 'short',
                                            day: 'numeric'
                                        });

                                        const statusColor = transaction.is_paid ? 'text-green-600' : 'text-blue-600';
                                        const statusText = transaction.is_paid ? 'Paid' : `Due: ${formatBdNumber(transaction.due_amount)} Tk`;
                                        const statusBg = transaction.is_paid ? 'bg-green-50 border-green-100' : 'bg-blue-50 border-blue-100';

                                        const transactionElement = document.createElement('div');
                                        transactionElement.className = `bg-white p-3 rounded-lg border ${statusBg}`;
                                        transactionElement.innerHTML = `
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">${transaction.product_name || 'N/A'}</div>
                                            <div class="text-xs text-gray-500 mt-1">${formattedDate} • ${transaction.variant_name || 'N/A'}</div>
                                            <div class="text-xs text-gray-500 mt-1">${transaction.quantity} × ${formatBdNumber(transaction.unit_price)} Tk</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-semibold ${statusColor}">
                                                ${formatBdNumber(transaction.total_amount)} Tk
                                            </div>
                                            <div class="text-xs ${transaction.is_paid ? 'text-green-500' : 'text-yellow-500'}">
                                                ${statusText}
                                            </div>
                                        </div>
                                    </div>
                                `;
                                        recentTransactionsElement.appendChild(transactionElement);
                                    });
                                } else {
                                    recentTransactionsElement.innerHTML = '<p class="text-sm text-gray-500">No recent transactions found</p>';
                                }

                                // Auto-fill amount with due amount if empty
                                if (!amountInput.value && data.due_amount > 0) {
                                    amountInput.value = parseFloat(data.due_amount).toFixed(2);
                                }
                            } else {
                                dueInfoContainer.classList.add('hidden');
                                recentTransactionsElement.innerHTML = '<p class="text-sm text-gray-500">No outstanding transactions found</p>';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching customer data:', error);
                            recentTransactionsElement.innerHTML = `
                        <div class="bg-red-50 border border-red-100 rounded-lg p-3">
                            <p class="text-sm text-red-600">Failed to load customer data. Please try again.</p>
                            <p class="text-xs text-red-500 mt-1">Error: ${error.message}</p>
                        </div>
                    `;
                        });
                });

                

                // Trigger change if customer is already selected (form validation error)
                if (customerSelect.value) {
                    customerSelect.dispatchEvent(new Event('change'));
                }

                form.addEventListener('submit', function(e) {
                    // Ensure hidden fields have unformatted values before submission
                    amountHiddenInput.value = parseFloat(amountInput.value.replace(/,/g, '')) || '';
                    discountAmountHiddenInput.value = parseFloat(discountAmountInput.value.replace(/,/g, '')) || '';
                });
            });

            function confirmPayment() {
                const form = document.getElementById('paymentForm');
                const customerSelect = document.getElementById('user_id');
                const selectedOption = customerSelect.options[customerSelect.selectedIndex];
                const customerName = selectedOption.dataset.name;
                const customerPhone = selectedOption.dataset.phone;
                const amount = document.getElementById('amount').value.replace(/,/g, '');
                const date = document.getElementById('payment_date').value;
                const autoAllocate = document.getElementById('mark_transactions_paid').checked;
                const settleAllDues = document.getElementById('settle_all_dues').checked;
                const discountAmount = document.getElementById('discount_amount').value.replace(/,/g, '');

                function formatBdNumber(num) {
                    return new Intl.NumberFormat('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num);
                }

                if (!customerSelect.value) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Please select a customer first',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                if (!amount || parseFloat(amount) <= 0) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Please enter a valid payment amount',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Confirm Payment',
                    html: `
                <div class="text-left">
                    <div class="mb-4">
                        <h3 class="font-medium text-gray-900">Payment Details</h3>
                        <div class="bg-gray-50 rounded-lg p-4 mt-2">
                            <div class="grid grid-cols-2 gap-2">
                                <div class="font-medium">Customer:</div>
                                <div>${customerName} ${customerPhone ? `(${customerPhone})` : ''}</div>
                                <div class="font-medium">Amount:</div>
                                <div class="font-bold">${formatBdNumber(amount)} Tk</div>
                                ${settleAllDues ? `
                                <div class="font-medium">Discount:</div>
                                <div class="font-bold">${formatBdNumber(discountAmount)} Tk</div>
                                ` : ''}
                                <div class="font-medium">Date:</div>
                                <div>${new Date(date).toLocaleDateString()}</div>
                                <div class="font-medium">Auto-allocate:</div>
                                <div class="${autoAllocate ? 'text-green-600 font-medium' : 'text-yellow-600 font-medium'}">
                                    ${autoAllocate ? 'Yes' : 'No'}
                                </div>
                                ${settleAllDues ? `
                                <div class="font-medium">Settle All Dues:</div>
                                <div class="text-green-600 font-medium">Yes</div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                    <div id="paymentPreview" class="text-sm text-gray-500 italic">
                        Loading payment preview...
                    </div>
                </div>
            `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirm Payment',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    focusConfirm: false,
                    customClass: {
                        popup: 'rounded-xl',
                        confirmButton: 'px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-lg',
                        cancelButton: 'px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg mr-3'
                    },
                    buttonsStyling: false,
                    didOpen: () => {
                        // Fetch allocation preview
                        const customerId = customerSelect.value;
                        fetch(`/api/customers/${customerId}/allocation-preview?amount=${amount}`)
                            .then(response => response.json())
                            .then(data => {
                                const previewElement = document.getElementById('paymentPreview');
                                if (data && data.transactions) {
                                    let previewHtml = '<div class="text-sm font-medium mb-1">This payment will be allocated to:</div>';
                                    data.transactions.forEach(t => {
                                        previewHtml += `
                                    <div class="flex justify-between py-1 border-b border-gray-100">
                                        <span>${t.product_name} (${t.variant_name})</span>
                                        <span class="font-medium">${parseFloat(t.allocated_amount).toFixed(2)} Tk</span>
                                    </div>
                                `;
                                    });
                                    if (data.remaining_amount > 0) {
                                        previewHtml += `
                                    <div class="flex justify-between py-1 font-medium text-blue-600 mt-2">
                                        <span>Remaining unallocated:</span>
                                        <span>${parseFloat(data.remaining_amount).toFixed(2)} Tk</span>
                                    </div>
                                `;
                                    }
                                    previewElement.innerHTML = previewHtml;
                                } else {
                                    previewElement.innerHTML = '<div class="text-sm">No allocation preview available</div>';
                                }
                            })
                            .catch(error => {
                                document.getElementById('paymentPreview').innerHTML =
                                    '<div class="text-sm text-red-500">Could not load allocation preview</div>';
                            });
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show processing alert
                        Swal.fire({
                            title: 'Processing Payment',
                            html: 'Please wait while we record your payment...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                                form.submit();
                            }
                        });
                    }
                });
            }
        </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\agro\resources\views/payments/create.blade.php ENDPATH**/ ?>