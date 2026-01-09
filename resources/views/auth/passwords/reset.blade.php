@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-3 bg-white rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold text-center">Reset Your Password</h1>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="space-y-1">
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input id="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="text-red-500 text-xs italic" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mt-4 space-y-1">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                @error('password')
                    <span class="text-red-500 text-xs italic" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mt-4 space-y-1">
                <label for="password-confirm" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input id="password-confirm" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div>
                <button type="submit" class="w-full px-4 py-2 mt-4 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
