<!-- resources/views/errors/500.blade.php -->
@extends('layouts.error')

@section('title', 'Server Error')
@section('error_title', 'Server Error')
@section('error_subtitle', 'Error 500')

@section('error_icon')
    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
    </svg>
@endsection

@section('error_message')
    <h3 class="text-lg font-medium text-gray-900 mb-2">Internal Server Error</h3>
    <p class="text-gray-600">Something went wrong on our end. We're working to fix it.</p>
@endsection

@section('error_actions')
    <a href="{{ url('/') }}" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
        Return to Home
    </a>
    <button onclick="window.location.reload()" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mt-3">
        Try Again
    </button>
@endsection
