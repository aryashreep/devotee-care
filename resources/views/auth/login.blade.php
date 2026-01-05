@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 mx-4 bg-white rounded-lg shadow-lg sm:mx-0">
        <div class="flex justify-center mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-24 h-24">
        </div>
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Devotee Care</h2>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Whoops!</strong>
                <span class="block sm:inline">There were some problems with your input.</span>
                <ul class="mt-3 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.request-otp') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="mobile_number" class="block text-sm font-medium text-gray-700">Mobile Number</label>
                <input type="text" name="mobile_number" id="mobile_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required autofocus>
            </div>

            <div class="flex items-center justify-end mt-6">
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Send OTP
                </button>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('register.step1.show') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    Don't have an account? Register
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
