<!-- resources/views/errors/403.blade.php -->
@extends('layouts.error')

@section('title', 'Access Denied')
@section('error_title', 'Access Denied')
@section('error_subtitle', 'Error 403')

@section('error_icon')
    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
    </svg>
@endsection

@section('error_message')
    <h3 class="text-lg font-medium text-gray-900 mb-2">Access Denied</h3>
    <p class="text-gray-600">You don't have permission to access this page.</p>
@endsection

@section('error_actions')
    <a href="{{ url('/') }}" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
        Return to Home
    </a>
    @auth
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 mt-3">
                Logout and Try Again
            </button>
        </form>
    @endauth
@endsection
