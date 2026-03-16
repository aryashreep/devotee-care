@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="flex w-full max-w-4xl bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="hidden md:block md:w-1/2 bg-cover" style="background-position: bottom; background-image: url('{{ asset('images/krishnasembrace.jpg') }}')"></div>

        <div class="w-full md:w-1/2 p-8">
            <div class="text-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-20 h-20 mx-auto mb-2">
                <h2 class="text-xl font-semibold">ISKCON Sri Jagannath Mandir</h2>
                <p class="text-center text-gray-600 mb-4">Devotee Care Management System</p>
            </div>

            @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf
                <input type="text" name="company_name" class="hidden" tabindex="-1" autocomplete="off">

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="mobile_number">Mobile Number</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('mobile_number') border-red-500 @enderror" id="mobile_number" name="mobile_number" type="text" value="{{ old('mobile_number') }}" placeholder="10-digit mobile number" required autofocus>
                    @error('mobile_number')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('password') border-red-500 @enderror" id="password" name="password" type="password" placeholder="Password" required>
                    @error('password')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="captcha_answer">Security Check: Enter <span class="font-bold">{{ $captchaChallenge }}</span></label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('captcha_answer') border-red-500 @enderror" id="captcha_answer" name="captcha_answer" type="number" required>
                    @error('captcha_answer')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-center">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full" type="submit">Login Securely</button>
                </div>
            </form>

            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600"><b>Don't have an account? <a href="{{ route('register.step1.show') }}" class="text-blue-500 hover:text-blue-700">Sign up</a></b></p>
                <p class="text-sm text-gray-600 mt-2">
                    <b>Help? <a href="https://wa.me/918147450705" target="_blank" rel="noopener noreferrer" class="text-green-600 hover:text-green-700">WhatsApp us! (+91 8147450705)</a></b>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
