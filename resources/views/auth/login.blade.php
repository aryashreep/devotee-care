@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="flex w-full max-w-4xl bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Left side with image -->
        <div class="hidden md:block md:w-1/2 bg-cover" style="background-position: bottom; background-image: url('{{ asset('images/krishnasembrace.jpg') }}')">
        </div>

        <!-- Right side with form -->
        <div class="w-full md:w-1/2 p-8">
            <div class="text-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-20 h-20 mx-auto mb-2">
                <h2 class="text-xl font-semibold">ISKCON Seshadripuram</h2>
                <p class="text-center text-gray-600 mb-4">Devotee Care Management System</p>
            </div>

            @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <div class="text-center mb-6">
                <div class="w-24 h-24 rounded-full bg-gray-200 mx-auto flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
            </div>

            <form id="login-form" method="POST" action="{{ route('login.request-otp') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="mobile_number">
                        Mobile Number
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('mobile_number') is-invalid @enderror" id="mobile_number" name="mobile_number" type="text" placeholder="Mobile Number" required autofocus>
                    @error('mobile_number')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6 hidden" id="password-field">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline @error('password') is-invalid @enderror" id="password" name="password" type="password" placeholder="******************">
                    @error('password')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-6">
                    <div>
                        <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="{{ route('password.request') }}">
                            Forgot Password?
                        </a>
                    </div>
                    <div class="flex rounded-md shadow-sm">
                        <button type="button" id="password-btn" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            Password
                        </button>
                        <button type="button" id="otp-btn" class="px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-blue-500 rounded-r-md hover:bg-blue-600 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            OTP
                        </button>
                    </div>
                </div>


                <div class="flex items-center justify-center">
                    <button id="submit-btn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full" type="submit">
                        Request OTP
                    </button>
                </div>
            </form>

            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600"><b>Don't have an account? <a href="{{ route('register.step1.show') }}" class="text-blue-500 hover:text-blue-700">Sign up</a></b></p>
            </div>
        </div>
    </div>
</div>

<script>
    const passwordBtn = document.getElementById('password-btn');
    const otpBtn = document.getElementById('otp-btn');
    const passwordField = document.getElementById('password-field');
    const submitBtn = document.getElementById('submit-btn');
    const loginForm = document.getElementById('login-form');
    const mobileNumberInput = document.getElementById('mobile_number');
    const passwordInput = document.getElementById('password');

    passwordBtn.addEventListener('click', () => {
        passwordBtn.classList.add('bg-blue-500', 'text-white');
        passwordBtn.classList.remove('bg-white', 'text-gray-700');
        otpBtn.classList.add('bg-white', 'text-gray-700');
        otpBtn.classList.remove('bg-blue-500', 'text-white');
        passwordField.classList.remove('hidden');
        submitBtn.textContent = 'Login';
        loginForm.action = "{{ route('login.password') }}";
        passwordInput.required = true;
    });

    otpBtn.addEventListener('click', () => {
        otpBtn.classList.add('bg-blue-500', 'text-white');
        otpBtn.classList.remove('bg-white', 'text-gray-700');
        passwordBtn.classList.add('bg-white', 'text-gray-700');
        passwordBtn.classList.remove('bg-blue-500', 'text-white');
        passwordField.classList.add('hidden');
        submitBtn.textContent = 'Request OTP';
        loginForm.action = "{{ route('login.request-otp') }}";
        passwordInput.required = false;
    });
</script>
@endsection