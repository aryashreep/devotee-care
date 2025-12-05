@extends('layouts.guest')

@section('title', 'Register - Step 1')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-lg p-8 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Create Your Account (Step 1 of 5)</h2>
        <form action="{{ route('register.step1.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                <input type="text" name="full_name" id="full_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Gender *</label>
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="radio" name="gender" value="Male" class="form-radio" required>
                        <span class="ml-2">Male</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" name="gender" value="Female" class="form-radio">
                        <span class="ml-2">Female</span>
                    </label>
                </div>
            </div>

            <div class="mb-4">
                <label for="photo" class="block text-sm font-medium text-gray-700">Photo *</label>
                <input type="file" name="photo" id="photo" class="mt-1 block w-full" required>
            </div>

            <div class="mb-4">
                <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth *</label>
                <input type="date" name="date_of_birth" id="date_of_birth" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Marital Status *</label>
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="radio" name="marital_status" value="Single" class="form-radio" required onchange="toggleAnniversary(false)">
                        <span class="ml-2">Single</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" name="marital_status" value="Married" class="form-radio" onchange="toggleAnniversary(true)">
                        <span class="ml-2">Married</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" name="marital_status" value="Divorced" class="form-radio" onchange="toggleAnniversary(false)">
                        <span class="ml-2">Divorced</span>
                    </label>
                </div>
            </div>

            <div class="mb-4 hidden" id="anniversary_date_div">
                <label for="marriage_anniversary_date" class="block text-sm font-medium text-gray-700">Marriage Anniversary Date</label>
                <input type="date" name="marriage_anniversary_date" id="marriage_anniversary_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password *</label>
                <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password *</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Next
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    function toggleAnniversary(show) {
        const anniversaryDiv = document.getElementById('anniversary_date_div');
        if (show) {
            anniversaryDiv.classList.remove('hidden');
        } else {
            anniversaryDiv.classList.add('hidden');
        }
    }
</script>
@endsection
