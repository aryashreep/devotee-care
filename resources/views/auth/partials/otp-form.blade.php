@if (session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

<form method="POST" action="{{ $verifyRoute }}">
    @csrf
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="otp">
            OTP
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('otp') is-invalid @enderror" id="otp" name="otp" type="text" placeholder="Enter OTP" required>
        @error('otp')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>
    <div class="flex items-center justify-center">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full" type="submit">
            Verify OTP
        </button>
    </div>
</form>

<div class="mt-4 text-center">
    <form method="POST" action="{{ $resendRoute }}">
        @csrf
        <button type="submit" class="text-sm text-blue-500 hover:text-blue-700">Resend OTP</button>
    </form>
</div>
