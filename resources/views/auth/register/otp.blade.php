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
            </div>
        </div>
    </div>
</div>
@endsection
