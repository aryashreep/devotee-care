@extends('layouts.guest')

@section('title', 'Verify OTP - Step 2.5')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Verify Your Mobile Number</h2>
        <p class="text-center text-gray-600 mb-8">
            An OTP has been sent to your mobile number and email. Please enter it below.
        </p>

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
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

        <form action="{{ route('register.otp.verify') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label for="otp" class="block text-sm font-medium text-gray-700">Enter OTP</label>
                <input type="text" name="otp" id="otp" class="mt-1 block w-full text-center text-2xl tracking-widest rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required maxlength="6" pattern="\d{6}">
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('register.step2.show') }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Verify & Continue
                </button>
            </div>
        </form>

        <div class="mt-4 text-center">
            <form action="{{ route('register.otp.resend') }}" method="POST" id="resend-form">
                @csrf
                <button type="submit" id="resend-btn" class="text-sm text-blue-500 hover:underline">Resend OTP</button>
            </form>
            <span id="resend-timer" class="text-sm text-gray-500" style="display: none;"></span>
        </div>

        <script>
            const resendBtn = document.getElementById('resend-btn');
            const resendTimer = document.getElementById('resend-timer');
            const resendForm = document.getElementById('resend-form');

            resendForm.addEventListener('submit', function () {
                resendBtn.style.display = 'none';
                resendTimer.style.display = 'inline';
                let seconds = 60;
                resendTimer.innerText = `Resend in ${seconds}s`;

                const interval = setInterval(() => {
                    seconds--;
                    resendTimer.innerText = `Resend in ${seconds}s`;
                    if (seconds <= 0) {
                        clearInterval(interval);
                        resendTimer.style.display = 'none';
                        resendBtn.style.display = 'inline';
                    }
                }, 1000);
            });
        </script>
    </div>
</div>
@endsection
