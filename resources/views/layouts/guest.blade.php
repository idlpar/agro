<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Talukder Agro Eco Farm') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🌱</text></svg>">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .bg-agro-pattern {
            background-color: #f7faf7;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23c6f6d5' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="font-sans text-gray-800 antialiased bg-agro-pattern">
<div class="min-h-screen flex flex-col sm:justify-center items-center p-6">
    <!-- Farm Logo and Name -->
    <div class="text-center mb-8">
        <div class="flex justify-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-green-800">Talukder Agro Eco Farm</h1>
        <p class="text-green-600 mt-1">Milk & Dairy Management System</p>
    </div>

    <!-- Content Card -->
    <div class="w-full sm:max-w-md bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Card Header (changes based on page) -->
        <div class="bg-green-600 py-4 px-6 text-center">
            @if(Request::is('login'))
                <h2 class="text-xl font-semibold text-white">Welcome Back</h2>
                <p class="text-green-100 text-sm mt-1">Sign in to your account</p>
            @elseif(Request::is('forgot-password'))
                <h2 class="text-xl font-semibold text-white">Reset Password</h2>
                <p class="text-green-100 text-sm mt-1">Enter your email to continue</p>
            @else
                <h2 class="text-xl font-semibold text-white">{{ $header ?? 'Welcome' }}</h2>
            @endif
        </div>

        <!-- Card Content -->
        <div class="p-6 sm:p-8">
            {{ $slot }}
        </div>

        <!-- Card Footer -->
        <div class="bg-gray-50 px-6 py-4 text-center">
            @if(Request::is('login'))
                <p class="text-sm text-gray-600">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-green-600 hover:text-green-800 font-medium">Contact admin</a>
                </p>
            @elseif(Request::is('forgot-password'))
                <a href="{{ route('login') }}" class="text-sm text-green-600 hover:text-green-800">Back to login</a>
            @else
                <p class="text-sm text-gray-600">© {{ date('Y') }} Talukder Agro Eco Farm</p>
            @endif
        </div>
    </div>

    <!-- Additional Info -->
    <div class="mt-8 text-center text-sm text-gray-500">
        <p>Need help? Contact support at <a href="mailto:support@talukderagro.com" class="text-green-600 hover:text-green-800">support@talukderagro.com</a></p>
    </div>
</div>
</body>
</html>
