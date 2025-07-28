<x-app-layout>
    <x-slot name="title">
        {{ $customer->name }} | {{ config('app.name') }}
    </x-slot>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                    {{ __('Customer Profile') }}
                </h2>
                <nav class="flex items-center space-x-2 mt-1">
                    <a href="{{ route('customers.index') }}" class="text-indigo-500 hover:text-indigo-700 transition-colors text-sm">
                        {{ __('Customers') }}
                    </a>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-600 text-sm font-medium">
                        {{ $customer->name }}
                    </span>
                </nav>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('customers.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:from-gray-600 hover:to-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ __('Back') }}
                </a>
                @can('update', $customer)
                    <a href="{{ route('customers.edit', $customer) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:from-blue-600 hover:to-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('Edit') }}
                    </a>
                @endcan
                @can('delete', $customer)
                    <form method="POST" action="{{ route('customers.destroy', $customer) }}" class="inline delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:from-red-600 hover:to-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            {{ __('Delete') }}
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow rounded-xl">
                <!-- Customer Header -->
                <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-blue-50">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="text-indigo-600 text-2xl font-bold">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $customer->name }}</h3>
                            <p class="text-sm text-gray-500">Customer since {{ $customer->created_at->format('F Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Customer Details -->
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Personal Information -->
                        <div class="bg-gray-50 p-5 rounded-lg shadow-sm">
                            <h4 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">{{ __('Personal Information') }}</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">{{ __('Full Name') }}</label>
                                    <p class="mt-1 text-sm text-gray-900 font-medium">{{ $customer->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">{{ __('Email Address') }}</label>
                                    <p class="mt-1 text-sm text-gray-900 font-medium">{{ $customer->email }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">{{ __('Phone Number') }}</label>
                                    <p class="mt-1 text-sm text-gray-900 font-medium">{{ $customer->phone }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="bg-gray-50 p-5 rounded-lg shadow-sm">
                            <h4 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">{{ __('Additional Information') }}</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">{{ __('Address') }}</label>
                                    <p class="mt-1 text-sm text-gray-900 font-medium">{{ $customer->address }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">{{ __('Created By') }}</label>
                                    <p class="mt-1 text-sm text-gray-900 font-medium">{{ $customer->creator->name ?? 'System' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">{{ __('Last Updated') }}</label>
                                    <p class="mt-1 text-sm text-gray-900 font-medium">{{ $customer->updated_at->format('M d, Y \a\t h:i A') }}</p>
                                </div>
                            </div>
                        </div>
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
