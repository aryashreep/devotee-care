@extends('layouts.guest')

@section('title', 'Verify OTP')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-center">
        <div class="w-full md:w-2/3 lg:w-1/2">
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h1 class="text-2xl font-bold mb-6 text-center">Step 2: Verify Mobile Number</h1>
                @include('auth.partials.otp-form', [
                    'verifyRoute' => route('register.otp.verify'),
                    'resendRoute' => route('register.otp.resend')
                ])
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600">
                        <b>Already have an account? <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-700">Login</a></b>
                    </p>
                    <p class="text-sm text-gray-600 mt-2">
                        <b>Help? <a href="https://wa.me/918147450705" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:text-blue-700">WhatsApp us!</a></b>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
