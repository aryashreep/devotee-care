@extends('layouts.guest')

@section('title', 'Register - Step 5')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-lg p-8 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Disclaimer (Step 5 of 5)</h2>

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

        <div class="prose">
            <p>The temple congregation management team works to facilitate the spiritual progress of all the associated devotees. And sometimes devotees are in need of guidance and without that, they may feel disconnected with spiritual life. For this, we are collecting details of the devotees connected to Sri Jagannath Mandir, Seshadripuram and associated Bhakti Sadans</p>
            <p class="font-bold mt-4">Key Benefits:</p>
            <ul class="list-disc list-inside">
                <li class="font-bold text-blue-600">Get connected to devotees, who are running various services, like provision stores, software engineers, doctors, astrologers etc. For the devotee community to progress, we should stay within devotee circles whenever possible</li>
                <li class="font-bold text-blue-600">Information about temple yatras, festivals and other events</li>
                <li class="font-bold text-blue-600">Temple team to analyse devotees of different age groups, and possibly prepare or providing better services like separate prasadam queue for senior citizens, medical camps, general counselling, Career & job counselling etc</li>
                <li class="font-bold text-blue-600">All sort of service opportunities connected to temple and Sadans</li>
                <li class="font-bold text-blue-600">Facilitate devotee get connecting to nearest bhakti sadans.</li>
                <li class="font-bold text-blue-600">Special Puja for Birthdays and Anniversaries</li>
            </ul>
        </div>
        <form action="{{ route('register.step5.store') }}" method="POST" class="mt-8">
            @csrf
            <div class="mb-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="disclaimer" class="form-checkbox" required>
                    <span class="ml-2">I hereby declare that the information furnished above is true and correct to the best of my knowledge. I voluntarily wish to be a part of ISKCON Seshadripuram’s spiritual and seva initiatives.</span>
                </label>
                @error('disclaimer')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('register.step4.show') }}" class="text-sm text-gray-600 hover:text-gray-900">Back</a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection