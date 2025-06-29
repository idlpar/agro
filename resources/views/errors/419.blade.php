<!-- resources/views/errors/419.blade.php -->
@extends('layouts.error')

@section('title', 'Session Expired')
@section('error_title', 'Session Expired')
@section('error_subtitle', 'Error 419')

@section('error_icon')
    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
@endsection

@section('error_message')
    <h3 class="text-lg font-medium text-gray-900 mb-2">Session Expired</h3>
    <p class="text-gray-600">Your session has expired. Please refresh and try again.</p>
@endsection

@section('error_actions')
    <button onclick="window.location.reload()" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        Refresh Page
    </button>
    <a href="{{ url('/') }}" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mt-3">
        Return to Home
    </a>
@endsection
