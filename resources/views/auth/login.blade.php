@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="flex w-full max-w-4xl bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Left side with image -->
        <div class="hidden md:block md:w-1/2 bg-cover" style="background-image: url('{{ asset('images/krishnasembrace.jpg') }}')">
        </div>

        <!-- Right side with form -->
        <div class="w-full md:w-1/2 p-8">
            <div class="text-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-20 h-20 mx-auto mb-2">
                <h2 class="text-xl font-semibold">ISKCON Seshadripuram</h2>
      <p class="text-center text-gray-600 mb-4">Devotee Care Management System</p>
            </div>

            <div class="text-center mb-6">
                <div class="w-24 h-24 rounded-full bg-gray-200 mx-auto flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="mobile_number">
                        Mobile Number
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="mobile_number" name="mobile_number" type="text" placeholder="Mobile Number" required autofocus>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" type="password" placeholder="******************" required>
                </div>
                <div class="flex items-center justify-center">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full" type="submit">
                        Sign In
                    </button>
                </div>

                <div class="mt-4 text-center">
                    <a href="#" class="text-sm text-blue-500 hover:text-blue-700">Forgot Password?</a>
                </div>
            </form>

            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">Don't have an account? <a href="{{ route('register.step1.show') }}" class="text-blue-500 hover:text-blue-700">Sign up</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
