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
        <div class="flex flex-wrap justify-between items-center max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4 md:mb-0">
                <h2 class="font-bold text-2xl text-gray-900">Management Dashboard</h2>
                <p class="text-sm text-gray-500 mt-1">Real-time insights into your business performance</p>
            </div>
            <a href="<?php echo e(route('reports.generate')); ?>" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition-all shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Generate Report
            </a>
        </div>
     <?php $__env->endSlot(); ?>

     <?php $__env->slot('title', null, []); ?> 
        <?php echo e(__('Dashboard')); ?> | <?php echo e(config('app.name')); ?>

     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Revenue Card -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Last Month Revenue</p>
                                <p class="text-2xl font-bold mt-1 text-gray-900">৳<?php echo e(number_format($lastMonthTotalRevenue, 2)); ?></p>
                                <div class="flex items-center mt-2">
                                    <span class="text-sm <?php echo e($revenueGrowth >= 0 ? 'text-green-600' : 'text-red-600'); ?>">
                                        <?php echo e($revenueGrowth >= 0 ? '↑' : '↓'); ?> <?php echo e(abs($revenueGrowth)); ?>%
                                    </span>
                                    <span class="text-xs text-gray-500 ml-2">vs this month (৳<?php echo e(number_format($thisMonthTotalRevenue, 2)); ?>)</span>
                                </div>
                            </div>
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3">
                        <a href="<?php echo e(route('transactions.index')); ?>" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center justify-between">
                            <span>View all transactions</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Customers Card -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Customers</p>
                                <p class="text-2xl font-bold mt-1 text-gray-900"><?php echo e($totalCustomers); ?></p>
                                <div class="flex items-center mt-2">
                                    <span class="text-sm text-blue-600"><?php echo e($activeCustomers); ?> active</span>
                                    <span class="text-xs text-gray-500 ml-2">• <?php echo e($newCustomers); ?> new</span>
                                </div>
                            </div>
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3">
                        <a href="<?php echo e(route('customers.index')); ?>" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center justify-between">
                            <span>Manage customers</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Payments Card -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Last Month Payments</p>
                                <p class="text-2xl font-bold mt-1 text-gray-900">৳<?php echo e(number_format($lastMonthTotalPayments, 2)); ?></p>
                                <div class="flex items-center mt-2">
                                    <span class="text-sm text-purple-600">৳<?php echo e(number_format($monthlyPayments, 2)); ?> this month</span>
                                    <span class="text-xs text-gray-500 ml-2">• ৳<?php echo e(number_format($outstandingPayments, 2)); ?> due</span>
                                </div>
                            </div>
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3">
                        <a href="<?php echo e(route('payments.index')); ?>" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center justify-between">
                            <span>View all payments</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Products Card -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Products</p>
                                <p class="text-2xl font-bold mt-1 text-gray-900"><?php echo e($totalProducts); ?></p>
                                <div class="flex items-center mt-2">
                                    <span class="text-sm text-amber-600"><?php echo e($totalVariants); ?> variants</span>
                                    <span class="text-xs text-gray-500 ml-2">• <?php echo e($topSellingVariants->sum('sold_quantity')); ?> sold</span>
                                </div>
                            </div>
                            <div class="p-3 rounded-full bg-amber-100 text-amber-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3">
                        <a href="<?php echo e(route('products.index')); ?>" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center justify-between">
                            <span>Manage products</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Historical Product Sales Chart -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Historical Product Sales</h3>
                            <select id="historicalPeriodSelect" class="text-sm px-3 py-2 bg-gray-100 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 appearance-none bg-[url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 24 24%22 stroke=%22%236b7280%22%3E%3Cpath stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%22%22 d=%22M19 9l-7 7-7-7%22/%3E%3C/svg%3E')] bg-no-repeat bg-right-0.5rem bg-center bg-[length:1.5em]">
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                        <div class="h-80">
                            <canvas id="historicalSalesChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Daily Product Sales Chart -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Daily Product Sales (Last 30 Days)</h3>
                            <a href="<?php echo e(route('products.index')); ?>" class="text-sm text-indigo-600 hover:text-indigo-800">View All</a>
                        </div>
                        <div class="h-80">
                            <canvas id="dailyProductSalesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Secondary Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Customer Growth -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 chart-container">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Customer Growth</h3>
                            <select id="customerGrowthPeriodSelect" class="text-sm px-3 py-2 bg-gray-100 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 appearance-none bg-[url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 24 24%22 stroke=%22%236b7280%22%3E%3Cpath stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%222%22 d=%22M19 9l-7 7-7-7%22/%3E%3C/svg%3E')] bg-no-repeat bg-right-0.5rem bg-center bg-[length:1.5em]">
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                        <div class="h-64">
                            <canvas id="customerGrowthChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Payment Collection -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 chart-container">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Payment Collection</h3>
                            <select id="paymentCollectionPeriodSelect" class="text-sm px-3 py-2 bg-gray-100 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 appearance-none bg-[url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 24 24%22 stroke=%22%236b7280%22%3E%3Cpath stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%222%22 d=%22M19 9l-7 7-7-7%22/%3E%3C/svg%3E')] bg-no-repeat bg-right-0.5rem bg-center bg-[length:1.5em]">
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                        <div class="h-64">
                            <canvas id="paymentCollectionChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Visit Completion -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 chart-container">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Visit Completion</h3>
                        <div class="h-64">
                            <canvas id="visitCompletionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Recent Transactions -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Transactions</h3>
                            <a href="<?php echo e(route('transactions.index')); ?>" class="text-sm text-indigo-600 hover:text-indigo-800">View All</a>
                        </div>
                        <div class="space-y-4">
                            <?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-medium text-gray-900"><?php echo e($transaction->customer->name ?? 'N/A'); ?></h4>
                                            <div class="text-sm text-gray-500 mt-1">
                                                <?php echo e($transaction->variant->product->name ?? 'N/A'); ?> <?php echo e($transaction->variant->name ?? 'N/A'); ?> x <?php echo e($transaction->quantity); ?> units
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium">৳<?php echo e(number_format($transaction->total_amount ?? 0, 2)); ?></div>
                                            <div class="text-xs <?php echo e($transaction->is_paid ? 'text-green-600' : 'text-amber-600'); ?>">
                                                <?php echo e($transaction->is_paid ? 'Paid' : 'Due'); ?>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-400">
                                        <?php echo e($transaction->transaction_date->format('M d, Y') ?? 'N/A'); ?> • <?php echo e($transaction->creator->name ?? 'N/A'); ?>

                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center text-gray-500">No recent transactions</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Recent Payments -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Payments</h3>
                            <a href="<?php echo e(route('payments.index')); ?>" class="text-sm text-indigo-600 hover:text-indigo-800">View All</a>
                        </div>
                        <div class="space-y-4">
                            <?php $__empty_1 = true; $__currentLoopData = $recentPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-medium text-gray-900"><?php echo e($payment->customer->name ?? 'N/A'); ?></h4>
                                            <div class="text-sm text-gray-500 mt-1">
                                                Collected by <?php echo e($payment->receiver->name ?? 'N/A'); ?>

                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-green-600">৳<?php echo e(number_format($payment->amount ?? 0, 2)); ?></div>
                                            <div class="text-xs text-gray-500">
                                                <?php echo e($payment->payment_date->format('M d, Y') ?? 'N/A'); ?>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                            <?php echo e($payment->transactions->count() ?? 0); ?> transactions
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center text-gray-500">No recent payments</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Visits -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Upcoming Visits</h3>
                            <a href="<?php echo e(route('visits.index')); ?>" class="text-sm text-indigo-600 hover:text-indigo-800">View All</a>
                        </div>
                        <div class="space-y-4">
                            <?php $__empty_1 = true; $__currentLoopData = $upcomingVisits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-900"><?php echo e($visit->purpose ?? 'N/A'); ?></h4>
                                            <div class="text-sm text-gray-500 mt-1">
                                                <?php echo e($visit->customer->name ?? 'N/A'); ?>

                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-medium text-gray-500">
                                                <?php echo e($visit->scheduled_date->format('M d') ?? 'N/A'); ?>

                                            </div>
                                            <div class="text-xs text-gray-400">
                                                <?php echo e($visit->scheduled_date->format('h:i A') ?? 'N/A'); ?>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 flex justify-between items-center">
                                        <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                            <?php echo e($visit->assignedTo->name ?? 'N/A'); ?>

                                        </span>
                                        <a href="<?php echo e(route('visits.edit', $visit)); ?>" class="text-xs text-indigo-600 hover:text-indigo-800">Details →</a>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center text-gray-500">No upcoming visits</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Due Transactions Section -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 mb-8 hover:shadow-lg transition-shadow">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Due Transactions</h3>
                        <div class="flex space-x-4">
                            <a href="<?php echo e(route('transactions.index', ['status' => 'due'])); ?>" class="text-sm text-indigo-600 hover:text-indigo-800">View All</a>
                            <a href="<?php echo e(route('payments.dueList')); ?>" class="text-sm text-indigo-600 hover:text-indigo-800">Due List</a>
                        </div>
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
                            <?php $__empty_1 = true; $__currentLoopData = $dueTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo e($transaction->transaction_date->format('M d, Y') ?? 'N/A'); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo e($transaction->customer->name ?? 'N/A'); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo e($transaction->customer->phone ?? 'N/A'); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo e($transaction->variant->product->name ?? 'N/A'); ?> - <?php echo e($transaction->variant->name ?? 'N/A'); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ৳<?php echo e(number_format($transaction->total_amount ?? 0, 2)); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                        ৳<?php echo e(number_format($transaction->paid_amount ?? 0, 2)); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                        ৳<?php echo e(number_format($transaction->due_amount ?? 0, 2)); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="<?php echo e(route('payments.create', ['transaction_id' => $transaction->id])); ?>" class="text-indigo-600 hover:text-indigo-800">Collect</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No due transactions</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <?php echo e($dueTransactions->links()); ?>

                    </div>
                </div>
            </div>

            <!-- Top Selling Products -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Top Selling Products</h3>
                        <a href="<?php echo e(route('products.index')); ?>" class="text-sm text-indigo-600 hover:text-indigo-800">View All</a>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <?php $__empty_1 = true; $__currentLoopData = $topSellingVariants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow text-center">
                                <div class="h-16 w-16 mx-auto bg-green-100 rounded-full flex items-center justify-center text-green-600 mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <h4 class="font-medium text-gray-900 text-sm"><?php echo e($variant->product->name ?? 'N/A'); ?></h4>
                                <p class="text-xs text-gray-500 mb-2"><?php echo e($variant->name ?? 'N/A'); ?></p>
                                <div class="text-sm font-medium text-gray-900">৳<?php echo e(number_format($variant->default_price ?? 0, 2)); ?></div>
                                <div class="text-xs text-gray-500 mt-1"><?php echo e($variant->sold_quantity_last_month ?? 0); ?> unit last month VS </br> <?php echo e($variant->sold_quantity_this_month ?? 0); ?> unit this month</div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center text-gray-500 col-span-full">No top selling products</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
        <script>
            // Color palette for charts
            const chartColors = {
                blue: 'rgba(59, 130, 246, 0.7)',
                green: 'rgba(16, 185, 129, 0.7)',
                purple: 'rgba(139, 92, 246, 0.7)',
                amber: 'rgba(245, 158, 11, 0.7)',
                red: 'rgba(239, 68, 68, 0.7)'
            };

            // Helper function to create gradient
            function createGradient(ctx, chartArea, colors) {
                const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                gradient.addColorStop(0, colors[0]);
                gradient.addColorStop(1, colors[1]);
                return gradient;
            }

            // Product Performance Chart (Daily Sales)
            const productPerformanceData = <?php echo json_encode($productPerformanceChartData ?? ['labels' => [], 'datasets' => []], 512) ?>;
            const dailyProductSalesCtx = document.getElementById('dailyProductSalesChart').getContext('2d');
            const dailyProductSalesChart = new Chart(dailyProductSalesCtx, {
                type: 'bar',
                data: {
                    labels: productPerformanceData.labels ?? [],
                    datasets: (productPerformanceData.datasets ?? []).map((dataset, index) => ({
                        label: dataset.label ?? 'Unknown',
                        data: dataset.data ?? [],
                        backgroundColor: function(context) {
                            const chart = context.chart;
                            const { ctx, chartArea } = chart;
                            if (!chartArea) return chartColors.blue;

                            const colors = [
                                `rgba(59, 130, 246, ${0.5 + (index * 0.1)})`,
                                `rgba(59, 130, 246, ${0.8 + (index * 0.05)})`
                            ];
                            return createGradient(ctx, chartArea, colors);
                        },
                        borderColor: chartColors.blue,
                        borderWidth: 1,
                        borderRadius: 4,
                        borderSkipped: false
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
                                color: '#1f2937',
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.95)',
                            titleFont: { family: 'Inter', size: 14, weight: '600' },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.raw} units`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: true,
                            ticks: {
                                font: { family: 'Inter', size: 12 },
                                color: '#6b7280',
                                maxRotation: 45,
                                minRotation: 45
                            },
                            grid: { display: false }
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true,
                            ticks: {
                                font: { family: 'Inter', size: 12 },
                                color: '#6b7280',
                                callback: function(value) {
                                    return `${value} units`;
                                }
                            },
                            grid: { color: 'rgba(209, 213, 219, 0.2)', drawBorder: false },
                            title: {
                                display: true,
                                text: 'Units Sold',
                                font: { family: 'Inter', size: 14, weight: '600' },
                                color: '#1f2937'
                            }
                        }
                    },
                    animation: {
                        duration: 1200,
                        easing: 'easeOutQuart'
                    }
                }
            });

            // Historical Product Sales Chart
            const historicalSalesData = <?php echo json_encode($historicalProductSalesChartData ?? ['labels' => [], 'datasets' => []], 512) ?>;
            const historicalSalesCtx = document.getElementById('historicalSalesChart').getContext('2d');
            const historicalSalesChart = new Chart(historicalSalesCtx, {
                type: 'line',
                data: {
                    labels: historicalSalesData.labels?.monthly ?? [],
                    datasets: (historicalSalesData.datasets ?? []).map((dataset, index) => ({
                        label: dataset.label ?? 'Unknown',
                        data: dataset.monthly ?? [],
                        backgroundColor: function(context) {
                            const chart = context.chart;
                            const { ctx, chartArea } = chart;
                            if (!chartArea) return chartColors.purple;

                            const colors = [
                                `rgba(139, 92, 246, ${0.3 + (index * 0.1)})`,
                                `rgba(139, 92, 246, ${0.6 + (index * 0.05)})`
                            ];
                            return createGradient(ctx, chartArea, colors);
                        },
                        borderColor: chartColors.purple,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: chartColors.purple,
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 8
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
                                color: '#1f2937',
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.95)',
                            titleFont: { family: 'Inter', size: 14, weight: '600' },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ৳${context.raw.toLocaleString()}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                font: { family: 'Inter', size: 12 },
                                color: '#6b7280'
                            },
                            grid: { display: false }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: { family: 'Inter', size: 12 },
                                color: '#6b7280',
                                callback: function(value) {
                                    return `৳${value.toLocaleString()}`;
                                }
                            },
                            grid: { color: 'rgba(209, 213, 219, 0.2)', drawBorder: false },
                            title: {
                                display: true,
                                text: 'Sales Amount',
                                font: { family: 'Inter', size: 14, weight: '600' },
                                color: '#1f2937'
                            }
                        }
                    },
                    animation: {
                        duration: 1200,
                        easing: 'easeOutQuart'
                    }
                }
            });

            // Update historical chart based on period selection
            document.getElementById('historicalPeriodSelect').addEventListener('change', function() {
                const period = this.value;
                historicalSalesChart.data.labels = historicalSalesData.labels[period] || [];
                historicalSalesChart.data.datasets.forEach((dataset, index) => {
                    dataset.data = historicalSalesData.datasets[index]?.[period] || [];
                });
                historicalSalesChart.update();
            });

            // Customer Growth Chart
            const customerGrowthData = <?php echo json_encode($customerGrowthChartData ?? ['labels' => [], 'data' => []], 512) ?>;
            const customerGrowthCtx = document.getElementById('customerGrowthChart').getContext('2d');
            const customerGrowthChart = new Chart(customerGrowthCtx, {
                type: 'bar',
                data: {
                    labels: customerGrowthData.labels?.monthly || [],
                    datasets: [{
                        label: 'New Customers',
                        data: customerGrowthData.data?.monthly || [],
                        backgroundColor: chartColors.green,
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
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

            // Payment Collection Chart
            const paymentCollectionData = <?php echo json_encode($paymentCollectionChartData ?? ['labels' => [], 'data' => []], 512) ?>;
            const paymentCollectionCtx = document.getElementById('paymentCollectionChart').getContext('2d');
            const paymentCollectionChart = new Chart(paymentCollectionCtx, {
                type: 'line',
                data: {
                    labels: paymentCollectionData.labels?.monthly || [],
                    datasets: [{
                        label: 'Payments Collected',
                        data: paymentCollectionData.data?.monthly || [],
                        backgroundColor: chartColors.blue,
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: 'rgba(59, 130, 246, 1)',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
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

            // Visit Completion Chart (Doughnut)
            const visitCompletionCtx = document.getElementById('visitCompletionChart').getContext('2d');
            const visitCompletionChart = new Chart(visitCompletionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Completed', 'Pending'],
                    datasets: [{
                        data: [<?php echo e($completedVisits); ?>, <?php echo e($totalVisits - $completedVisits); ?>],
                        backgroundColor: [chartColors.green, chartColors.amber],
                        borderColor: ['rgba(16, 185, 129, 1)', 'rgba(245, 158, 11, 1)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { family: 'Inter', size: 12, weight: '500' },
                                color: '#1f2937',
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            titleFont: { family: 'Inter', size: 13, weight: '600' },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 10,
                            cornerRadius: 6,
                            boxPadding: 6
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });

            // Update charts based on period selection
            document.getElementById('customerGrowthPeriodSelect')?.addEventListener('change', function() {
                const period = this.value;
                customerGrowthChart.data.labels = customerGrowthData.labels?.[period] || [];
                customerGrowthChart.data.datasets[0].data = customerGrowthData.data?.[period] || [];
                customerGrowthChart.update();
            });

            document.getElementById('paymentCollectionPeriodSelect')?.addEventListener('change', function() {
                const period = this.value;
                paymentCollectionChart.data.labels = paymentCollectionData.labels?.[period] || [];
                paymentCollectionChart.data.datasets[0].data = paymentCollectionData.data?.[period] || [];
                paymentCollectionChart.update();
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
            select {
                transition: all 0.2s ease;
                border: 1px solid #e5e7eb;
                background: #f9fafb;
            }
            select:focus {
                border-color: #4f46e5;
                box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            }
        </style>
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
<?php /**PATH C:\laragon\www\agro\resources\views/dashboard/admin.blade.php ENDPATH**/ ?>