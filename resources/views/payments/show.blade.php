<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Payment Receipt #{{ $payment->id }}
                </h2>
                <div class="flex items-center space-x-2 mt-2">
                    <a href="{{ route('payments.index') }}" class="text-blue-500 hover:text-blue-700 text-sm">
                        All Payments
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-600 text-sm">Details</span>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('payments.edit', $payment->id) }}"
                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 border border-transparent rounded-lg font-medium text-xs text-white uppercase tracking-widest shadow-sm hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('payments.destroy', $payment->id) }}" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 border border-transparent rounded-lg font-medium text-xs text-white uppercase tracking-widest shadow-sm hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Status Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 rounded-xl border border-green-200 flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            @elseif (session('error'))
                <div class="mb-6 p-4 bg-red-50 rounded-xl border border-red-200 flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            @endif

            <!-- Payment Receipt Card -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl">
                <div class="p-6 sm:p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Payment Summary -->
                        <div class="lg:col-span-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 mb-1">Payment Summary</h3>
                                    <p class="text-sm text-gray-500">Receipt #{{ $payment->id }}</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $payment->payment_date->format('M d, Y') }}
                                </span>
                            </div>

                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Customer Card -->
                                <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
                                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</h4>
                                    <p class="mt-1 text-xl font-bold text-gray-900">{{ $payment->customer->name }}</p>
                                    <div class="mt-4 flex items-center text-sm text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        {{ $payment->customer->email ?? 'No email' }}
                                    </div>
                                </div>

                                <!-- Payment Details Card -->
                                <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
                                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Details</h4>
                                    <div class="mt-3 space-y-3">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Amount:</span>
                                            <span class="font-bold text-gray-900">{{ number_format($payment->amount, 2) }} Tk</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Allocated:</span>
                                            <span class="font-bold {{ $payment->allocated_amount == $payment->amount ? 'text-green-600' : 'text-blue-600' }}">
                                                {{ number_format($payment->allocated_amount, 2) }} Tk
                                            </span>
                                        </div>
                                        @if($payment->unallocated_amount > 0)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Unallocated:</span>
                                                <span class="font-bold text-yellow-600">{{ number_format($payment->unallocated_amount, 2) }} Tk</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Notes Section -->
                            <div class="mt-6">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Notes</h4>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                    <p class="text-gray-700">{{ $payment->notes ?? 'No notes provided' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Receiver & Actions -->
                        <div class="space-y-6">
                            <!-- Received By Card -->
                            <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
                                <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider">Received By</h4>
                                <div class="mt-3 flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                        {{ substr($payment->receiver->name, 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $payment->receiver->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $payment->created_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Unallocated Payment Actions -->
                            @if($payment->unallocated_amount > 0)
                                <div class="bg-yellow-50 p-5 rounded-xl border border-yellow-100">
                                    <h4 class="text-sm font-medium text-yellow-800 mb-2">Unallocated Amount Available</h4>
                                    <p class="text-sm text-yellow-700 mb-3">
                                        You have {{ number_format($payment->unallocated_amount, 2) }} Tk that hasn't been allocated to any transactions.
                                    </p>
                                    <a href="{{ route('payments.edit', $payment->id) }}"
                                       class="w-full inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 border border-transparent rounded-lg font-medium text-xs text-white uppercase tracking-widest shadow-sm hover:from-yellow-600 hover:to-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition">
                                        Allocate to Transactions
                                    </a>
                                </div>
                            @endif

                            <!-- Print/Download Options -->
                            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Export Receipt</h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <button type="button" class="inline-flex justify-center items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        Print
                                    </button>
                                    <button type="button" class="inline-flex justify-center items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Allocated Transactions Section -->
                    <div class="mt-10">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Allocated Transactions</h3>

                        @if($payment->transactions->count() > 0)
                            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Allocated</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($payment->transactions as $transaction)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $transaction->transaction_date->format('d M Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $transaction->variant->product->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $transaction->variant->name }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $transaction->quantity }} × {{ number_format($transaction->unit_price, 2) }} Tk
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ number_format($transaction->total_amount, 2) }} Tk
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ number_format($transaction->pivot->allocated_amount, 2) }} Tk
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                        {{ $transaction->is_paid ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ $transaction->is_paid ? 'Paid' : 'Partial' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot class="bg-gray-50 border-t border-gray-200">
                                        <tr>
                                            <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">
                                                Total Allocated:
                                            </td>
                                            <td class="px-6 py-3 text-sm font-medium text-gray-900">
                                                {{ number_format($payment->allocated_amount, 2) }} Tk
                                            </td>
                                            <td colspan="2"></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="bg-white border border-gray-200 rounded-xl p-8 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h4 class="mt-3 text-lg font-medium text-gray-900">No transactions allocated</h4>
                                <p class="mt-1 text-gray-500">This payment hasn't been allocated to any transactions yet.</p>
                                <div class="mt-6">
                                    <a href="{{ route('payments.edit', $payment->id) }}"
                                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 border border-transparent rounded-lg font-medium text-sm text-white shadow-sm hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                        Allocate to Transactions
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
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
                            text: "You won't be able to revert this payment deletion!",
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
