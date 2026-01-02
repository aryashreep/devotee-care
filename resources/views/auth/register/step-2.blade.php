@extends('layouts.guest')

@section('title', 'Register - Step 2')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-lg p-8 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Contact Details (Step 2 of 5)</h2>

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

        <form action="{{ route('register.step2.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="mobile_number" class="block text-sm font-medium text-gray-700">Mobile Number *</label>
                <input type="text" name="mobile_number" id="mobile_number" value="{{ old('mobile_number') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                @error('mobile_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700">Address *</label>
                <input type="text" name="address" id="address" value="{{ old('address') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="state" class="block text-sm font-medium text-gray-700">State *</label>
                <select name="state" id="state" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="">Select a state</option>
                    @foreach($states as $state)
                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                    @endforeach
                </select>
                @error('state')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="city" class="block text-sm font-medium text-gray-700">City *</label>
                <select name="city" id="city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                    <option value="">Select a city</option>
                </select>
                @error('city')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>


            <div class="mb-4">
                <label for="pincode" class="block text-sm font-medium text-gray-700">Pincode *</label>
                <input type="text" name="pincode" id="pincode" value="{{ old('pincode') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                @error('pincode')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                <input type="text" name="country" id="country" value="India" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" readonly>
                @error('country')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('register.step1.show') }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Next
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#state').select2({
            tags: true
        });
        $('#city').select2({
            tags: true
        });

        $('#state').on('change', function() {
            var stateId = $(this).val();
            if (stateId) {
                $.ajax({
                    url: '/api/states/' + stateId + '/cities',
                    type: "GET",
                    dataType: "json",
                    beforeSend: function() {
                        $('#city').empty().append('<option value="">Loading...</option>');
                    },
                    success: function(data) {
                        $('#city').empty().append('<option value="">Select a city</option>');
                        $.each(data, function(key, value) {
                            $('#city').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#city').empty().append('<option value="">Select a city</option>');
            }
        });
    });
</script>
@endpush
