@extends('layouts.guest')

@section('title', 'Enter OTP')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="flex w-full max-w-4xl bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Left side with image -->
        <div class="hidden md:block md:w-1/2 bg-cover" style="background-position: bottom;background-image: url('{{ asset('images/krishnasembrace.jpg') }}')">
        </div>

        <!-- Right side with form -->
        <div class="w-full md:w-1/2 p-8">
            <div class="text-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-20 h-20 mx-auto mb-2">
                <h2 class="text-xl font-semibold">ISKCON Seshadripuram</h2>
                <p class="text-center text-gray-600 mb-4">Devotee Care Management System</p>
            </div>

            <p class="text-center text-gray-600 mb-4">An OTP has been sent to your mobile number and email address.</p>

            @include('auth.partials.otp-form', [
            'verifyRoute' => route('login.otp.verify'),
            'resendRoute' => route('login.otp.resend')
            ])
        </div>
    </div>
</div>
@endsection