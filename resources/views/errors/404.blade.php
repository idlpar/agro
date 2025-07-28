<!-- resources/views/errors/404.blade.php -->
@extends('layouts.error')

@section('title', 'Page Not Found')
@section('error_title', 'Page Not Found')
@section('error_subtitle', 'Error 404')

@section('error_icon')
    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
@endsection

@section('error_message')
    <h3 class="text-lg font-medium text-gray-900 mb-2">Oops! Page not found</h3>
    <p class="text-gray-600">The page you're looking for doesn't exist or has been moved.</p>
@endsection

@section('error_actions')
    <a href="{{ url('/') }}" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
        Return to Home
    </a>
@endsection
