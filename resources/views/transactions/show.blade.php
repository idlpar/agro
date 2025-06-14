<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between md:gap-6">
            <!-- Left: Title + Breadcrumb -->
            <div>
                <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                    {{ __('Transaction Details') }} #{{ $transaction->id }}
                </h2>
                <nav class="flex items-center space-x-2 text-sm mt-1">
                    <a href="{{ route('transactions.index') }}" class="text-indigo-500 hover:text-indigo-700">
                        {{ __('Transactions') }}
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-gray-600">
                        {{ __('Details') }}
                    </span>
                </nav>
            </div>

            <!-- Center: Payment Status + Date -->
            <div class="flex items-center gap-3 mt-4 md:mt-0">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-lg font-medium
                             {{ $transaction->is_paid ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                    {{ $transaction->payment_status }}
                </span>
                <span class="text-lg text-gray-500">
                    {{ $transaction->transaction_date->format('M d, Y') }}
                </span>
            </div>

            <!-- Right: Back Button -->
            <div class="mt-4 md:mt-0">
                <a href="{{ route('transactions.index') }}"
                   class="inline-flex items-center px-3 py-1.5 bg-gray-600 border border-transparent rounded-md text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 19l-7-7 7-7"/>
                    </svg>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Show Transaction') }} | {{ config('app.name') }}
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Main Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <!-- Card Header -->
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">
                            Transaction Summary
                        </h3>
                        <div class="flex space-x-3">
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('transactions.edit', $transaction->id) }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('transactions.destroy', $transaction->id) }}" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        <!-- Customer & Product Info -->
                        <div class="space-y-6">
                            <!-- Customer Card -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-500 mb-2">Customer</h4>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-lg font-medium text-gray-900">{{ $transaction->customer->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $transaction->customer->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Card -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-500 mb-2">Product Details</h4>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-lg font-medium text-gray-900">{{ $transaction->variant->product->name }}</p>
                                        <p class="text-sm text-gray-500">Variant: {{ $transaction->variant->name }}</p>
                                        <p class="text-sm text-gray-500 mt-1">SKU: {{ $transaction->variant->sku ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Transaction Details -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500 mb-4">Transaction Details</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Quantity</p>
                                    <p class="text-lg font-medium text-gray-900">{{ $transaction->quantity }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Unit Price</p>
                                    <p class="text-lg font-medium text-gray-900">{{ number_format($transaction->unit_price, 2) }} Tk</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Discount</p>
                                    <p class="text-lg font-medium text-gray-900">{{ number_format($transaction->discount_amount, 2) }} Tk</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Total Amount</p>
                                    <p class="text-lg font-medium text-gray-900">{{ number_format($transaction->total_amount, 2) }} Tk</p>
                                </div>
                            </div>

                            <!-- Payment Status -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-sm text-gray-500">Payment Status</p>
                                        <p class="text-lg font-medium text-gray-900">

                                            @if($transaction->isPartiallyPaid())
                                                ({{ number_format($transaction->paid_amount, 2) }} Tk paid)
                                            @else
                                                {{ $transaction->payment_status }}
                                            @endif
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                              {{ $transaction->is_paid ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $transaction->payment_status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes Section -->
                    @if($transaction->notes)
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Additional Notes</h4>
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM ascended.1-1.414-2.828a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            {{ $transaction->notes }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Payments Section -->
            @if($transaction->payments->count() > 0)
                <div class="mt-8 bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900">
                            Payment History
                        </h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($transaction->payments as $payment)
                            <div class="px-6 py-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900">
                                                Payment #{{ $payment->id }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                {{ $payment->payment_date->format('M d, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-900">
                                            {{ number_format($payment->amount, 2) }} Tk
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Received by {{ $payment->receiver->name }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('styles')
        <style>
            /* Elegant red pattern overlay for SweetAlert2 */
            .swal2-container {
                background-image:
                    repeating-linear-gradient(
                        45deg,
                        rgba(255, 0, 0, 0.08),
                        rgba(255, 0, 0, 0.08) 10px,
                        rgba(255, 100, 100, 0.08) 10px,
                        rgba(255, 100, 100, 0.08) 20px
                    );
                background-color: rgba(255, 0, 0, 0.05) !important;
                backdrop-filter: blur(6px);
            }

            .swal2-popup {
                border-radius: 1rem;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
                animation: fadeInZoom 0.4s ease;
                border: 1px solid rgba(255, 0, 0, 0.2);
            }

            @keyframes fadeInZoom {
                0% {
                    transform: scale(0.85);
                    opacity: 0;
                }
                100% {
                    transform: scale(1);
                    opacity: 1;
                }
            }

            .swal2-title {
                color: #b91c1c; /* Dark red */
                font-weight: 700;
                font-size: 1.75rem;
            }

            .swal2-confirm {
                background: linear-gradient(to right, #dc2626, #ef4444) !important;
                color: white !important;
                border: none !important;
                border-radius: 0.5rem !important;
                padding: 0.6rem 1.2rem !important;
                font-weight: bold !important;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: all 0.2s ease-in-out;
            }

            .swal2-cancel {
                background: linear-gradient(to right, #f87171, #fca5a5) !important;
                color: white !important;
                border: none !important;
                border-radius: 0.5rem !important;
                padding: 0.6rem 1.2rem !important;
                font-weight: bold !important;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: all 0.2s ease-in-out;
                margin-right: 0.75rem;
            }

            .swal2-confirm:hover {
                background: linear-gradient(to right, #b91c1c, #dc2626) !important;
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            }

            .swal2-cancel:hover {
                background: linear-gradient(to right, #ef4444, #f87171) !important;
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Delete confirmation with SweetAlert
            document.addEventListener('DOMContentLoaded', function() {
                const deleteForms = document.querySelectorAll('.delete-form');

                deleteForms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'Cancel',
                            focusCancel: true,
                            reverseButtons: true,
                            backdrop: `
                                rgba(0,0,123,0.4)
                                url("/images/nyan-cat.gif")
                                left top
                                no-repeat
                            `,
                            customClass: {
                                confirmButton: 'bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-200',
                                cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 mr-3'
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
